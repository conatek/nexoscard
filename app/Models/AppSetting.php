<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            if (! $setting) return $default;

            return match ($setting->type) {
                'integer' => (int) $setting->value,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                default   => $setting->value,
            };
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget("app_setting_{$key}");
    }

    public static function getTrialDays(): int
    {
        return (int) static::get('trial_days', 7);
    }

    /**
     * Días antes del vencimiento en los que se avisa al cliente. Se guarda como lista
     * separada por comas ("3,1") para que el Master pueda ajustar la cadencia sin tocar
     * código.
     */
    public static function getTrialReminderDays(): array
    {
        $raw = (string) static::get('trial_reminder_days', '3,1');

        $days = array_filter(
            array_map('intval', explode(',', $raw)),
            fn (int $d) => $d > 0
        );

        // De mayor a menor: el aviso lejano primero.
        rsort($days);

        return array_values(array_unique($days));
    }

    public static function getGracePeriodDays(): int
    {
        return (int) static::get('grace_period_days', 10);
    }

    public static function getSupportEmail(): string
    {
        return (string) static::get('support_email', 'soporte@nexoscard.com');
    }

    /**
     * Numero de WhatsApp normalizado (solo digitos), o null si no esta configurado.
     * Se limpia aqui para que la UI no tenga que adivinar el formato que cargo el Master.
     */
    public static function getSupportWhatsapp(): ?string
    {
        $raw = preg_replace('/\D/', '', (string) static::get('support_whatsapp', ''));

        return $raw !== '' ? $raw : null;
    }

    /**
     * Datos de contacto que el frontend necesita para pintar los botones de soporte.
     */
    public static function publicContact(): array
    {
        return [
            'support_email'    => static::getSupportEmail(),
            'support_whatsapp' => static::getSupportWhatsapp(),
        ];
    }
}
