<?php

namespace App\Services;

use App\Models\Payment;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;

class MercadoPagoService
{
    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(config('mercadopago.access_token'));
    }

    /**
     * Crear una preferencia de pago (Checkout Pro).
     * Retorna la URL de redireccion y el preference_id.
     */
    public function createPreference(Payment $payment, string $planName, string $period): array
    {
        $client = new PreferenceClient();

        $preferenceData = [
            'items' => [
                [
                    'id'          => 'plan-' . $payment->id,
                    'title'       => "NexosCard - Plan {$planName} ({$period})",
                    'quantity'    => 1,
                    'unit_price'  => (float) $payment->amount,
                    'currency_id' => config('mercadopago.currency'),
                ],
            ],
            'payer' => [
                'email' => $this->getPayerEmail($payment),
            ],
            'back_urls' => [
                'success' => config('mercadopago.back_urls.success'),
                'failure' => config('mercadopago.back_urls.failure'),
                'pending' => config('mercadopago.back_urls.pending'),
            ],
            'auto_return' => 'approved',
            'external_reference' => $this->generateReference($payment),
            'metadata' => [
                'payment_id'     => $payment->id,
                'company_id'     => $payment->company_id,
                'plan_id'        => $payment->metadata['plan_id'] ?? null,
                'billing_period' => $payment->metadata['billing_period'] ?? null,
            ],
        ];

        // Solo incluir notification_url si esta configurada
        $notificationUrl = config('mercadopago.notification_url');
        if ($notificationUrl) {
            $preferenceData['notification_url'] = $notificationUrl;
        }

        try {
            $preference = $client->create($preferenceData);
        } catch (MPApiException $e) {
            Log::error('MercadoPago createPreference error', [
                'status'  => $e->getStatusCode(),
                'content' => $e->getApiResponse()?->getContent(),
            ]);
            throw $e;
        }

        return [
            'preference_id' => $preference->id,
            'init_point'    => config('mercadopago.sandbox')
                ? $preference->sandbox_init_point
                : $preference->init_point,
        ];
    }

    /**
     * Crear un pago con token del Brick (Payments API).
     */
    public function createPayment(array $data): array
    {
        $client = new PaymentClient();

        try {
            $payload = [
                'transaction_amount' => (float) $data['transaction_amount'],
                'token'              => $data['token'],
                'installments'       => (int) ($data['installments'] ?? 1),
                'payment_method_id'  => $data['payment_method_id'],
                'issuer_id'          => $data['issuer_id'] ?? null,
                'payer'              => [
                    'email'          => $data['payer_email'],
                    'identification' => [
                        'type'   => $data['identification_type'] ?? 'CC',
                        'number' => $data['identification_number'] ?? '',
                    ],
                ],
            ];

            // Referencia al pago local. Sin esto el webhook solo puede casar por el id de
            // MercadoPago, que se guarda *después* de que esta llamada responde: una
            // notificación que llegue antes (o un fallo al escribir) deja el pago huérfano
            // y la suscripción sin activar. Con PSE o efectivo, donde el webhook es la
            // única confirmación, eso significa cobrar y no entregar.
            if (! empty($data['external_reference'])) {
                $payload['external_reference'] = $data['external_reference'];
            }

            if (! empty($data['metadata'])) {
                $payload['metadata'] = $data['metadata'];
            }

            $payment = $client->create($payload);

            return [
                'id'                 => $payment->id,
                'status'             => $payment->status,
                'status_detail'      => $payment->status_detail,
                'payment_method_id'  => $payment->payment_method_id,
                'payment_type_id'    => $payment->payment_type_id,
                'transaction_amount' => $payment->transaction_amount,
                'date_approved'      => $payment->date_approved,
            ];
        } catch (MPApiException $e) {
            $content = $e->getApiResponse()?->getContent();
            Log::error('MercadoPago createPayment error', [
                'status'  => $e->getStatusCode(),
                'content' => $content,
            ]);
            return [
                'id'     => null,
                'status' => 'error',
                'status_detail' => is_array($content) ? ($content['message'] ?? 'Error de pago') : 'Error de pago',
            ];
        }
    }

    /**
     * Consultar un pago por ID en la API de MercadoPago.
     */
    public function getPayment(string $paymentId): ?array
    {
        $client = new PaymentClient();

        try {
            $payment = $client->get((int) $paymentId);

            return [
                'id'                 => $payment->id,
                'status'             => $payment->status,
                'status_detail'      => $payment->status_detail,
                'payment_method_id'  => $payment->payment_method_id,
                'payment_type_id'    => $payment->payment_type_id,
                'transaction_amount' => $payment->transaction_amount,
                'currency_id'        => $payment->currency_id,
                'external_reference' => $payment->external_reference,
                'order_id'           => $payment->order?->id,
                'metadata'           => (array) $payment->metadata,
                'date_approved'      => $payment->date_approved,
                'payer_email'        => $payment->payer?->email,
            ];
        } catch (\Exception $e) {
            Log::error('MercadoPago getPayment error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validar la firma del webhook de MercadoPago.
     * Header x-signature: ts=TIMESTAMP,v1=HASH
     */
    public function validateWebhookSignature(string $xSignature, string $xRequestId, string $dataId): bool
    {
        $secret = config('mercadopago.webhook_secret');

        if (! $secret) {
            // Cómodo en local, pero en producción significa aceptar notificaciones sin
            // verificar de quién vienen. Se deja pasar para no tumbar un flujo que ya
            // esté funcionando, pero tiene que verse en los logs.
            if (app()->environment('production')) {
                Log::error(
                    'MercadoPago: MERCADOPAGO_WEBHOOK_SECRET vacio en produccion. '
                    . 'Las notificaciones se estan aceptando sin validar la firma.'
                );
            }

            return true;
        }

        // Parsear ts y v1 del header
        $parts = [];
        foreach (explode(',', $xSignature) as $part) {
            $segments = explode('=', $part, 2);
            if (count($segments) === 2) {
                $parts[trim($segments[0])] = trim($segments[1]);
            }
        }

        $ts = $parts['ts'] ?? '';
        $v1 = $parts['v1'] ?? '';

        if (! $ts || ! $v1) {
            return false;
        }

        // Construir template para HMAC
        $template = "id:{$dataId};request-id:{$xRequestId};ts:{$ts};";

        // Calcular HMAC-SHA256
        $calculated = hash_hmac('sha256', $template, $secret);

        return hash_equals($calculated, $v1);
    }

    /**
     * Mapear estado de MercadoPago a estado interno.
     */
    public function mapStatus(string $mpStatus): string
    {
        return match ($mpStatus) {
            'approved'                          => 'approved',
            'rejected', 'cancelled'             => 'declined',
            'pending', 'in_process', 'authorized' => 'pending',
            'refunded', 'charged_back'          => 'refunded',
            default                             => 'pending',
        };
    }

    /**
     * Mapear metodo de pago de MercadoPago a label interno.
     */
    public function mapPaymentMethod(string $methodId, string $typeId): string
    {
        return match ($typeId) {
            'credit_card'    => 'credit_card',
            'debit_card'     => 'debit_card',
            'bank_transfer'  => 'pse',
            'ticket'         => 'cash',
            'digital_wallet' => 'wallet',
            default          => $typeId ?: $methodId,
        };
    }

    /**
     * Formato `NEXOS-{id local}-{timestamp}`. Lo parsea
     * `ProcessMercadoPagoNotification::findLocalPayment()`, así que el segundo segmento
     * debe seguir siendo el id local.
     */
    public function generateReference(Payment $payment): string
    {
        return 'NEXOS-' . $payment->id . '-' . time();
    }

    private function getPayerEmail(Payment $payment): string
    {
        return $payment->company->users()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['Admin', 'Super']))
            ->first()?->email
            ?? $payment->company->owner?->email
            ?? 'comprador@nexoscard.com';
    }
}
