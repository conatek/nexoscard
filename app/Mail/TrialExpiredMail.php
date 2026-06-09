<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Company $company,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tu periodo de prueba en NexosCard ha finalizado');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.trial-expired');
    }
}
