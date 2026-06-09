<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Company $company,
        public int $daysRemaining,
        public Carbon $trialEndsAt,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Tu prueba en NexosCard vence en {$this->daysRemaining} dias");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.trial-expiring');
    }
}
