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

        // Mapear estado y metodo de pago
        $internalStatus = $mpService->mapStatus($mpPayment['status']);
        $paymentMethod  = $mpService->mapPaymentMethod(
            $mpPayment['payment_method_id'] ?? '',
            $mpPayment['payment_type_id'] ?? ''
        );

        // Idempotencia: MercadoPago reenvia la misma notificacion varias veces, asi que
        // se corta cuando el estado no cambia. Antes se cortaba ante *cualquier* pago ya
        // aprobado, y eso se tragaba en silencio los reembolsos y contracargos: el cliente
        // se quedaba con el año de servicio y en la base figuraba cobrado.
        if ($localPayment->status === $internalStatus) {
            Log::info("MercadoPago: payment #{$localPayment->id} ya esta en '{$internalStatus}', omitiendo");
            return;
        }

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

        if ($internalStatus === 'refunded') {
            $this->handleRefund($localPayment);
        }

        Log::info("MercadoPago: payment #{$localPayment->id} actualizado a '{$internalStatus}'");
    }

    /**
     * Un reembolso o contracargo deja una suscripción vigente pagada con dinero que ya no
     * está. Se pasa a `past_due` en vez de cancelarla:
     *
     * - Cancelar de golpe tumba la tarjeta del cliente en el acto, y una disputa puede
     *   resolverse a favor del comercio; el daño de equivocarse ahí es alto.
     * - Dejarla intacta regala hasta un año de servicio y depende de que alguien mire el
     *   log.
     *
     * `past_due` es exactamente el estado que ya existe para "hay que cobrar de nuevo":
     * la tarjeta sigue publicada los días de gracia, el cliente ve el banner de renovar,
     * y si nadie hace nada el comando diario la expira sola. No hace falta maquinaria
     * nueva ni una intervención manual para que la historia termine bien.
     */
    private function handleRefund(Payment $payment): void
    {
        if (! $payment->subscription_id) {
            return;
        }

        $subscription = $payment->subscription;

        // Si ya no está vigente no hay nada que degradar: puede haberse renovado con otro
        // pago posterior, y en ese caso tumbarla sería cobrar dos veces el error.
        if (! $subscription || ! $subscription->isActive()) {
            return;
        }

        $subscription->update(['status' => 'past_due']);

        Log::warning(
            "MercadoPago: payment #{$payment->id} reembolsado. La suscripcion "
            . "#{$subscription->id} de la empresa #{$payment->company_id} pasa a past_due; "
            . 'la tarjeta sigue publicada durante los dias de gracia.'
        );
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

        if (! $planId) {
            Log::warning("MercadoPago: payment #{$payment->id} sin plan_id en metadata");
            return;
        }

        $plan = Plan::find($planId);
        if (! $plan) return;

        $company = $payment->company;
        if (! $company) return;

        // El periodo lo resuelve el servicio a partir del ciclo del plan.
        $subscription = $subscriptionService->activateSubscription($company, $plan, 'mercadopago');

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
