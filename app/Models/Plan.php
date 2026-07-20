<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'price_regular',
        'offer_price',
        'offer_ends_at',
        'billing_period',
        'max_cards',
        'max_products',
        'max_services',
        'available_templates',
        'show_watermark',
        'features',
        'is_active',
        'is_default',
        'sort_order',
    ];

    protected $casts = [
        'price_regular'       => 'decimal:2',
        'offer_price'         => 'decimal:2',
        'offer_ends_at'       => 'datetime',
        'max_cards'           => 'integer',
        'max_products'        => 'integer',
        'max_services'        => 'integer',
        'available_templates' => 'array',
        'show_watermark'      => 'boolean',
        'features'            => 'array',
        'is_active'           => 'boolean',
        'is_default'          => 'boolean',
        'sort_order'          => 'integer',
    ];

    /**
     * Se exponen al JSON para que checkout, landing, panel admin y GET /subscription
     * consuman exactamente el mismo precio, sin reimplementar la regla en JS.
     */
    protected $appends = [
        'effective_price',
        'is_offer_active',
        'discount_percent',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * El plan sobre el que corren el trial y las altas. Cae al primer plan activo para
     * que un `is_default` mal configurado no rompa el registro de usuarios.
     */
    public static function default(): ?self
    {
        return static::query()->default()->active()->first()
            ?? static::query()->active()->orderBy('sort_order')->first();
    }

    /* ==================== Precio ==================== */

    /**
     * Hay oferta si hay precio de oferta y o bien no tiene fecha de fin (oferta
     * indefinida) o la fecha aún no pasó.
     */
    public function isOfferActive(): bool
    {
        if ($this->offer_price === null) {
            return false;
        }

        return $this->offer_ends_at === null || $this->offer_ends_at->isFuture();
    }

    /**
     * Precio que se cobra hoy. Única fuente de verdad: el servidor nunca debe usar un
     * monto que venga del cliente.
     */
    public function effectivePrice(): float
    {
        return (float) ($this->isOfferActive() ? $this->offer_price : $this->price_regular);
    }

    public function discountPercent(): int
    {
        $regular = (float) $this->price_regular;

        if (!$this->isOfferActive() || $regular <= 0) {
            return 0;
        }

        return (int) round((1 - $this->effectivePrice() / $regular) * 100);
    }

    /**
     * Fin del periodo según el ciclo del plan. Reemplaza los `addYear()` que estaban
     * duplicados en los tres sitios que activan una suscripción.
     */
    public function periodEnd(Carbon $from): Carbon
    {
        return $this->billing_period === 'monthly'
            ? $from->copy()->addMonth()
            : $from->copy()->addYear();
    }

    /* ==================== Accessors expuestos ==================== */
    // `decimal:2` serializa como string en JSON; se castea a float para que el front
    // sume en vez de concatenar.

    public function getEffectivePriceAttribute(): float
    {
        return $this->effectivePrice();
    }

    public function getIsOfferActiveAttribute(): bool
    {
        return $this->isOfferActive();
    }

    public function getDiscountPercentAttribute(): int
    {
        return $this->discountPercent();
    }

    /* ==================== Límites ==================== */

    public function isUnlimited(string $resource): bool
    {
        $field = "max_{$resource}";
        return is_null($this->$field);
    }

    public function getLimit(string $resource): ?int
    {
        $field = "max_{$resource}";
        return $this->$field;
    }
}
