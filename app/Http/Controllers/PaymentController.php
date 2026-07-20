<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessMercadoPagoNotification;
use App\Mail\SubscriptionActivatedMail;
use App\Models\Payment;
use App\Models\Plan;
use App\Services\MercadoPagoService;
use App\Services\SubscriptionService;
use App\Traits\HasCompanyAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    use HasCompanyAccess;

    public function __construct(
        private MercadoPagoService $mpService,
        private SubscriptionService $subscriptionService,
    ) {}

    /**
     * Procesar pago con token del Brick (CardPayment).
     * POST /api/payments/process
     */
    public function processPayment(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id'             => 'required|exists:plans,id',
            // El monto real siempre lo decide el servidor. Este campo es solo el precio
            // que el cliente tenía en pantalla, para abortar si la oferta venció entre
            // que se pintó el checkout y se envió el token.
            'expected_amount'     => 'nullable|numeric',
            'token'               => 'required|string',
            'payment_method_id'   => 'required|string',
            'installments'        => 'required|integer|min:1',
            'issuer_id'           => 'nullable|string',
            'payer_email'         => 'required|email',
            'identification_type' => 'nullable|string',
            'identification_number' => 'nullable|string',
        ]);

        $user    = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['message' => 'No tienes una empresa asociada.'], 422);
        }

        $plan = Plan::active()->findOrFail($request->plan_id);

        // Precio del servidor, nunca el que mande el cliente.
        $amount = $plan->effectivePrice();

        if ($amount <= 0) {
            return response()->json(['message' => 'Este plan no requiere pago.'], 422);
        }

        // La oferta pudo vencer mientras el usuario completaba el formulario: antes de
        // cobrar de más, se aborta y el front recarga el precio.
        if ($request->filled('expected_amount')
            && abs((float) $request->expected_amount - $amount) > 0.01) {
            return response()->json([
                'message'        => 'El precio cambió mientras completabas el pago. Revisa el nuevo valor antes de continuar.',
                'price_changed'  => true,
                'current_amount' => $amount,
            ], 422);
        }

        // Crear payment pendiente
        $payment = Payment::create([
            'company_id' => $company->id,
            'amount'     => $amount,
            'currency'   => config('mercadopago.currency', 'COP'),
            'status'     => 'pending',
            'metadata'   => [
                'plan_id'        => $plan->id,
                'plan_name'      => $plan->display_name,
                'billing_period' => $plan->billing_period,
                'price_regular'  => (float) $plan->price_regular,
                'offer_applied'  => $plan->isOfferActive(),
            ],
        ]);

        // Crear pago en MercadoPago via Payments API
        $mpResult = $this->mpService->createPayment([
            'transaction_amount'    => (float) $amount,
            'token'                 => $request->token,
            'installments'          => $request->installments,
            'payment_method_id'     => $request->payment_method_id,
            'issuer_id'             => $request->issuer_id,
            'payer_email'           => $request->payer_email,
            'identification_type'   => $request->identification_type,
            'identification_number' => $request->identification_number,
        ]);

        // Mapear estado
        $internalStatus = $mpResult['id']
            ? $this->mpService->mapStatus($mpResult['status'])
            : 'declined';

        $paymentMethod = $this->mpService->mapPaymentMethod(
            $mpResult['payment_method_id'] ?? '',
            $mpResult['payment_type_id'] ?? ''
        );

        // Actualizar payment local
        $payment->update([
            'mercadopago_payment_id' => $mpResult['id'],
            'status'                 => $internalStatus,
            'payment_method'         => $paymentMethod,
            'response_code'          => $mpResult['status_detail'],
            'paid_at'                => $internalStatus === 'approved'
                ? ($mpResult['date_approved'] ?? now())
                : null,
            'metadata'               => array_merge(
                $payment->metadata ?? [],
                ['mp_response' => $mpResult]
            ),
        ]);

        // Si aprobado, activar suscripcion
        if ($internalStatus === 'approved') {
            $subscription = $this->subscriptionService->activateSubscription($company, $plan, 'mercadopago');

            $payment->update(['subscription_id' => $subscription->id]);

            // Email de activacion
            $owner = $company->owner;
            if ($owner) {
                Mail::to($owner->email)->queue(
                    new SubscriptionActivatedMail($owner, $company, $plan, $subscription, $payment)
                );
            }
        }

        return response()->json([
            'status'        => $internalStatus,
            'status_detail' => $mpResult['status_detail'] ?? null,
            'payment_id'    => $payment->id,
            'mp_payment_id' => $mpResult['id'],
            'amount'        => $payment->amount,
            'currency'      => $payment->currency,
        ]);
    }

    /**
     * Historial de pagos de la empresa.
     * GET /api/payments/history
     */
    public function history(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasRole('Master')) {
            $payments = Payment::with('company:id,name')
                ->latest()
                ->paginate(20);
        } else {
            $company = $user->company;
            if (!$company) {
                return response()->json(['data' => []]);
            }

            $payments = $company->payments()
                ->latest()
                ->paginate(20);
        }

        return response()->json($payments);
    }

    /**
     * Webhook de MercadoPago (publico, sin auth).
     * POST /api/mercadopago/webhook
     */
    public function webhook(Request $request): JsonResponse
    {
        $xSignature = $request->header('x-signature', '');
        $xRequestId = $request->header('x-request-id', '');
        $dataId     = $request->input('data.id', '');

        if (!$this->mpService->validateWebhookSignature($xSignature, $xRequestId, (string) $dataId)) {
            Log::warning('MercadoPago webhook: firma invalida');
            return response()->json(['message' => 'Firma invalida'], 400);
        }

        $type = $request->input('type');
        if ($type !== 'payment') {
            return response()->json(['message' => 'OK']);
        }

        $mpPaymentId = $request->input('data.id');
        if ($mpPaymentId) {
            ProcessMercadoPagoNotification::dispatch((string) $mpPaymentId);
        }

        return response()->json(['message' => 'OK']);
    }
}
