<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo_path',
        'logo_public_id',
        'icon_path',
        'icon_public_id',
        'address',
        'city',
        'country',
        'web',
        'my_business',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'tiktok',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class)->orderBy('order');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('order');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function settings()
    {
        return $this->hasOne(CompanySetting::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->active();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()->active()->latest()->first();
    }

    public function currentPlan(): ?Plan
    {
        return $this->activeSubscription()?->plan;
    }

    /**
     * La suscripción más reciente sin filtrar por estado.
     *
     * `activeSubscription()` usa el scope `active()`, que solo abarca trial y active. Eso
     * está bien para *conceder* permisos, pero no para **reportar** el estado: sirve la
     * visibilidad pública durante los días de gracia (past_due), y es lo que consultan las
     * dos lecturas que alimentan la UI (`/api/subscription` y `/api/dashboard`). Si ahí se
     * usara `activeSubscription()`, un cliente vencido sería indistinguible de uno que
     * nunca tuvo suscripción y el banner de "Renovar" saldría vacío.
     */
    public function latestSubscription(): ?Subscription
    {
        // Se desempata por id: al reactivar, la suscripción nueva y la vieja pueden
        // compartir `created_at` al segundo, y ordenar solo por fecha dejaría la tarjeta
        // fuera de línea después de haber pagado.
        return $this->subscriptions()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Si la tarjeta pública sigue online. Durante el periodo de gracia (past_due) se
     * mantiene: los días de gracia existen para que un cobro rechazado no tumbe de
     * inmediato la presencia del cliente.
     */
    public function hasPublicAccess(): bool
    {
        $subscription = $this->latestSubscription();

        if (!$subscription) {
            return false;
        }

        return in_array($subscription->status, ['trial', 'active', 'past_due'], true);
    }

    /**
     * Icono cuadrado para el acceso directo que el visitante guarda en su dispositivo.
     *
     * Prioridad: icono propio > logotipo > icono de NexosCard. El logo sirve de respaldo
     * porque ya lo tienen cargado casi todas las empresas, aunque al ser normalmente
     * apaisado se vea peor que un icono hecho a propósito.
     *
     * Se fuerza fondo sólido y formato PNG: iOS no soporta canal alfa en el icono de la
     * pantalla de inicio y pinta de negro lo que sea transparente.
     */
    public function shortcutIconUrl(int $size): ?string
    {
        $source = $this->icon_path ?: $this->logo_path;

        if (!$source) {
            return null;
        }

        // Las imágenes viven en Cloudinary, que redimensiona y convierte por URL. Si
        // alguna no lo estuviera, se devuelve tal cual antes que romper el head.
        if (!str_contains($source, '/image/upload/')) {
            return $source;
        }

        $url = str_replace(
            '/image/upload/',
            "/image/upload/w_{$size},h_{$size},c_pad,b_white/",
            $source
        );

        return preg_replace('/\.[a-zA-Z0-9]+(\?.*)?$/', '.png$1', $url);
    }

    /**
     * Obtener o crear settings con valores por defecto
     */
    public function getOrCreateSettings(): CompanySetting
    {
        $settings = $this->settings()->first();

        if (!$settings) {
            $templateName = 'impulso';
            $defaults = config("templates.schemas.{$templateName}", []);

            // Extraer solo los valores del schema
            $values = [];
            foreach ($defaults as $sectionKey => $section) {
                if (!is_array($section)) continue;
                $values[$sectionKey] = [];
                foreach ($section as $fieldKey => $field) {
                    if (str_starts_with($fieldKey, '_')) continue;
                    if (is_array($field) && isset($field['value'])) {
                        $values[$sectionKey][$fieldKey] = $field['value'];
                    }
                }
            }

            $settings = $this->settings()->create([
                'template_name' => $templateName,
                'customization' => $values,
            ]);
        }

        return $settings;
    }
}
