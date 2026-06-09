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

class WelcomeTrialMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Company $company,
        public int $trialDays,
        public Carbon $trialEndsAt,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Bienvenido a NexosCard');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welcome-trial');
    }
}
