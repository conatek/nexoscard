<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionActivatedMail;
use App\Mail\SubscriptionExpiredMail;
use App\Mail\SubscriptionExpiringMail;
use App\Mail\TrialExpiredMail;
use App\Mail\TrialExpiringMail;
use App\Mail\WelcomeTrialMail;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestSubscriptionEmails extends Command
{
    protected $signature = 'emails:test-subscription {type : welcome|expiring|expired|activated|renewal|subscription-expired} {--email= : Email destino}';
    protected $description = 'Enviar un email de prueba del flujo de suscripcion';

    public function handle(): int
    {
        $type  = $this->argument('type');
        $email = $this->option('email') ?: 'test@example.com';

        $user    = User::first();
        $company = $user?->company;

        if (! $user || ! $company) {
            $this->error('No hay usuarios en la base de datos.');
            return Command::FAILURE;
        }

        $mailable = match ($type) {
            'welcome'              => new WelcomeTrialMail($user, $company, 30, now()->addDays(30)),
            'expiring'             => new TrialExpiringMail($user, $company, 7, now()->addDays(7)),
            'expired'              => new TrialExpiredMail($user, $company),
            'activated'            => new SubscriptionActivatedMail(
                $user, $company,
                Plan::default() ?? Plan::first(),
                Subscription::first() ?? new Subscription(['current_period_end' => now()->addMonth()]),
                Payment::first() ?? new Payment(['amount' => 49900, 'currency' => 'COP']),
            ),
            'renewal'              => new SubscriptionExpiringMail(
                $user, $company,
                Plan::default() ?? Plan::first(),
                15, now()->addDays(15),
                \App\Models\AppSetting::getGracePeriodDays(),
            ),
            'subscription-expired' => new SubscriptionExpiredMail($user, $company, Plan::default()),
            default                => null,
        };

        if (! $mailable) {
            $this->error("Tipo invalido: {$type}. Use: welcome, expiring, expired, activated, renewal, subscription-expired");
            return Command::FAILURE;
        }

        Mail::to($email)->send($mailable);
        $this->info("Email '{$type}' enviado a {$email}");

        return Command::SUCCESS;
    }
}
