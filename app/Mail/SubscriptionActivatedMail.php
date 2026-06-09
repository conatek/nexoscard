<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Company $company,
        public Plan $plan,
        public Subscription $subscription,
        public Payment $payment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Plan {$this->plan->display_name} activado exitosamente");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.subscription-activated');
    }
}
