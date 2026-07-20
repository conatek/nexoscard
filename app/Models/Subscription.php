<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'plan_id',
        'billing_period',
        'reminders_sent',
        'status',
        'payment_method',
        'payu_subscription_id',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'cancelled_at',
    ];

    protected $casts = [
        'trial_ends_at'        => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end'   => 'datetime',
        'cancelled_at'         => 'datetime',
        'reminders_sent'       => 'array',
    ];

    /**
     * Los recordatorios se marcan por día de antelación ("3", "1") junto con la fecha de
     * vencimiento a la que corresponden: si la suscripción se renueva, los avisos del
     * ciclo anterior no deben bloquear los del nuevo.
     */
    private function reminderKey(int $days): string
    {
        return $days . '@' . ($this->trial_ends_at?->toDateString() ?? 'sin-fecha');
    }

    public function wasReminderSent(int $days): bool
    {
        return in_array($this->reminderKey($days), $this->reminders_sent ?? [], true);
    }

    public function markReminderSent(int $days): void
    {
        $sent = $this->reminders_sent ?? [];
        $sent[] = $this->reminderKey($days);

        $this->update(['reminders_sent' => array_values(array_unique($sent))]);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // --- Scopes ---

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['trial', 'active']);
    }

    public function scopeExpiringSoon(Builder $query, int $days = 5): Builder
    {
        return $query->whereIn('status', ['trial', 'active'])
            ->where('current_period_end', '<=', now()->addDays($days));
    }

    // --- Helpers ---

    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active']);
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trial';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function daysRemaining(): int
    {
        // En trial manda `trial_ends_at`: es la fecha con la que se disparan los
        // recordatorios por correo, y si el banner contara por `current_period_end`
        // ambos podrían contradecirse cuando el trial se extiende.
        $end = $this->status === 'trial' && $this->trial_ends_at
            ? $this->trial_ends_at
            : $this->current_period_end;

        if (!$end) {
            return 0;
        }

        // ceil y no cast a int: truncar dejaba que un vencimiento a 2 dias exactos
        // (1.9999 dias) se mostrara como 1, sub-reportando hasta un dia completo.
        return max(0, (int) ceil(now()->diffInDays($end, false)));
    }
}
