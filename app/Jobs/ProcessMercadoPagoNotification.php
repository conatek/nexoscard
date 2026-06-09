<?php

namespace App\Jobs;

use App\Mail\SubscriptionActivatedMail;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\MercadoPagoService;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessMercadoPagoNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        private string $mercadoPagoPaymentId
    ) {}

    public function handle(SubscriptionService $subscriptionService): void
    {
        $mpService = app(MercadoPagoService::class);

        // Consultar el pago en la API de MercadoPago
        $mpPayment = $mpService->getPayment($this->mercadoPagoPaymentId);
        if (! $mpPayment) {
            Log::warning("MercadoPago: no se pudo obtener pago {$this->mercadoPagoPaymentId}");
            return;
        }

        // Buscar payment local
        $localPayment = $this->findLocalPayment($mpPayment);
        if (! $localPayment) {
            Log::warning("MercadoPago: payment local no encontrado para MP ID {$this->mercadoPagoPaymentId}");
            return;
        }

        // Idempotencia: no re-procesar pagos ya aprobados
        if ($localPayment->status === 'approved') {
            Log::info("MercadoPago: payment #{$localPayment->id} ya esta aprobado, omitiendo");
            return;
        }

        // Mapear estado y metodo de pago
        $internalStatus = $mpService->mapStatus($mpPayment['status']);
        $paymentMethod  = $mpService->mapPaymentMethod(
            $mpPayment['payment_method_id'] ?? '',
            $mpPayment['payment_type_id'] ?? ''
        );

        // Actualizar payment local
        $localPayment->update([
            'mercadopago_payment_id' => $mpPayment['id'],
            'mercadopago_order_id'   => $mpPayment['order_id'],
            'status'                 => $internalStatus,
            'payment_method'         => $paymentMethod,
            'response_code'          => $mpPayment['status_detail'],
            'paid_at'                => $internalStatus === 'approved'
                ? ($mpPayment['date_approved'] ?? now())
                : null,
            'metadata'               => array_merge(
                $localPayment->metadata ?? [],
                ['mp_response' => $mpPayment]
            ),
        ]);

        // Si aprobado, activar suscripcion
        if ($internalStatus === 'approved') {
            $this->activateSubscription($localPayment, $subscriptionService);
        }

        Log::info("MercadoPago: payment #{$localPayment->id} actualizado a '{$internalStatus}'");
    }

    private function findLocalPayment(array $mpPayment): ?Payment
    {
        // Por mercadopago_payment_id (si ya fue actualizado previamente)
        $payment = Payment::where('mercadopago_payment_id', $mpPayment['id'])->first();
        if ($payment) return $payment;

        // Por external_reference (NEXOS-{id}-{timestamp})
        $externalRef = $mpPayment['external_reference'] ?? '';
        if ($externalRef) {
            $parts = explode('-', $externalRef);
            $localId = $parts[1] ?? null;
            if ($localId) {
                $payment = Payment::find($localId);
                if ($payment) return $payment;
            }
        }

        // Por metadata.payment_id
        $metadata = $mpPayment['metadata'] ?? [];
        if (isset($metadata['payment_id'])) {
            return Payment::find($metadata['payment_id']);
        }

        return null;
    }

    private function activateSubscription(Payment $payment, SubscriptionService $subscriptionService): void
    {
        $metadata = $payment->metadata ?? [];
        $planId = $metadata['plan_id'] ?? null;
        $period = $metadata['billing_period'] ?? 'monthly';

        if (! $planId) {
            Log::warning("MercadoPago: payment #{$payment->id} sin plan_id en metadata");
            return;
        }

        $plan = Plan::find($planId);
        if (! $plan) return;

        $company = $payment->company;
        if (! $company) return;

        $subscription = $subscriptionService->activateSubscription($company, $plan, 'mercadopago');

        // Ajustar periodo si es anual
        if ($period === 'yearly') {
            $subscription->update([
                'current_period_end' => now()->addYear(),
            ]);
        }

        // Vincular payment a la suscripcion
        $payment->update(['subscription_id' => $subscription->id]);

        // Email de activacion
        $owner = $company->owner;
        if ($owner) {
            Mail::to($owner->email)->queue(
                new SubscriptionActivatedMail($owner, $company, $plan, $subscription, $payment)
            );
        }
    }
}
