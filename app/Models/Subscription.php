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

    /** Avisos de fin de prueba, anclados a `trial_ends_at`. */
    public const REMINDER_TRIAL = 'trial';

    /** Avisos de renovación de un plan pagado, anclados a `current_period_end`. */
    public const REMINDER_RENEWAL = 'renovacion';

    /**
     * Los recordatorios se marcan por día de antelación ("3", "1") junto con la fecha de
     * vencimiento a la que corresponden: si la suscripción se renueva, los avisos del
     * ciclo anterior no deben bloquear los del nuevo.
     *
     * Cada tipo se ancla a su propia fecha. Un aviso de renovación anclado a
     * `trial_ends_at` (null en una suscripción pagada) daría siempre la misma clave
     * `sin-fecha` y solo se enviaría el primero, para siempre.
     *
     * El formato del trial se deja **exactamente como estaba**, sin prefijo: cambiarlo
     * invalidaría las claves ya guardadas y reenviaría el aviso a todas las pruebas en
     * curso el día del despliegue.
     */
    private function reminderKey(int $days, string $type): string
    {
        if ($type === self::REMINDER_TRIAL) {
            return $days . '@' . ($this->trial_ends_at?->toDateString() ?? 'sin-fecha');
        }

        return $type . ':' . $days . '@' . ($this->current_period_end?->toDateString() ?? 'sin-fecha');
    }

    public function wasReminderSent(int $days, string $type = self::REMINDER_TRIAL): bool
    {
        return in_array($this->reminderKey($days, $type), $this->reminders_sent ?? [], true);
    }

    public function markReminderSent(int $days, string $type = self::REMINDER_TRIAL): void
    {
        $sent = $this->reminders_sent ?? [];
        $sent[] = $this->reminderKey($days, $type);

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
