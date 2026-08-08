<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Aviso de renovación para quien ya paga.
 *
 * El plan es anual y **no se renueva solo**: MercadoPago cobra una vez, no hay suscripción
 * recurrente. Sin este correo el cliente se entera de que venció cuando su tarjeta pública
 * ya está fuera de línea.
 */
class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Company $company,
        public ?Plan $plan,
        public int $daysRemaining,
        public Carbon $periodEndsAt,
        public int $graceDays,
    ) {}

    public function envelope(): Envelope
    {
        // El asunto lleva el número de días porque es lo que decide si se abre hoy o
        // "más tarde". En singular cuando corresponde: "en 1 dias" se lee a máquina.
        $plazo = $this->daysRemaining === 1
            ? 'manana'
            : "en {$this->daysRemaining} dias";

        return new Envelope(subject: "Tu plan en NexosCard se renueva {$plazo}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscription-expiring');
    }
}
