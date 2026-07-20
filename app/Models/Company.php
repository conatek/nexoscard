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
     * `activeSubscription()` usa el scope `active()`, que solo abarca trial y active: no
     * sirve para decidir la visibilidad pública porque dejaría la tarjeta caída durante
     * los días de gracia (past_due).
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
