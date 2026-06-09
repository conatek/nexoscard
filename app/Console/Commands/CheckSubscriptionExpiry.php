<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiredMail;
use App\Mail\TrialExpiredMail;
use App\Mail\TrialExpiringMail;
use App\Models\AppSetting;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckSubscriptionExpiry extends Command
{
    protected $signature = 'subscriptions:check-expiry';
    protected $description = 'Verificar vencimientos de suscripciones y actualizar estados';

    public function handle(): int
    {
        $this->handleTrialReminders();
        $this->handleExpiredTrials();
        $this->handlePastDueSubscriptions();
        $this->handleFullyExpired();

        return Command::SUCCESS;
    }

    /**
     * Enviar recordatorio a trials que vencen en 7 dias
     */
    private function handleTrialReminders(): void
    {
        $reminderDays = 7;
        $targetDate = now()->addDays($reminderDays)->startOfDay();

        $expiringSoon = Subscription::where('status', 'trial')
            ->whereDate('trial_ends_at', $targetDate)
            ->with('company.owner')
            ->get();

        foreach ($expiringSoon as $subscription) {
            $owner = $subscription->company?->owner;
            if ($owner) {
                Mail::to($owner->email)->queue(
                    new TrialExpiringMail($owner, $subscription->company, $reminderDays, $subscription->trial_ends_at)
                );
                $this->info("Recordatorio enviado: empresa #{$subscription->company_id}");
            }
        }
    }

    /**
     * Suscripciones trial que vencieron → expired
     */
    private function handleExpiredTrials(): void
    {
        $expired = Subscription::where('status', 'trial')
            ->where('trial_ends_at', '<', now())
            ->with('company.owner')
            ->get();

        foreach ($expired as $subscription) {
            $subscription->update(['status' => 'expired']);

            $owner = $subscription->company?->owner;
            if ($owner) {
                Mail::to($owner->email)->queue(
                    new TrialExpiredMail($owner, $subscription->company)
                );
            }

            $this->info("Trial expirado: empresa #{$subscription->company_id}");
        }

        if ($expired->count() > 0) {
            $this->info("{$expired->count()} trial(s) expirado(s).");
        }
    }

    /**
     * Suscripciones active cuyo periodo termino → past_due
     */
    private function handlePastDueSubscriptions(): void
    {
        $pastDue = Subscription::where('status', 'active')
            ->where('current_period_end', '<', now())
            ->get();

        foreach ($pastDue as $subscription) {
            $subscription->update(['status' => 'past_due']);
            $this->info("Suscripcion vencida (past_due): empresa #{$subscription->company_id}");
        }

        if ($pastDue->count() > 0) {
            $this->info("{$pastDue->count()} suscripcion(es) pasada(s) a past_due.");
        }
    }

    /**
     * Suscripciones past_due con mas de N dias de gracia → expired + degradar rol
     */
    private function handleFullyExpired(): void
    {
        $graceDays = AppSetting::getGracePeriodDays();
        $fullyExpired = Subscription::where('status', 'past_due')
            ->where('current_period_end', '<', now()->subDays($graceDays))
            ->with('company.owner', 'plan')
            ->get();

        foreach ($fullyExpired as $subscription) {
            $subscription->update(['status' => 'expired']);

            $company = $subscription->company;
            if ($company) {
                // Email de expiracion
                $owner = $company->owner;
                if ($owner) {
                    Mail::to($owner->email)->queue(
                        new SubscriptionExpiredMail($owner, $company, $subscription->plan)
                    );
                }
            }

            $this->info("Suscripcion expirada completamente: empresa #{$subscription->company_id}");
        }

        if ($fullyExpired->count() > 0) {
            $this->info("{$fullyExpired->count()} suscripcion(es) expirada(s) completamente.");
        }
    }
}
