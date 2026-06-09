<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Company $company,
        public ?Plan $previousPlan = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tu suscripcion en NexosCard ha expirado');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscription-expired');
    }
}
