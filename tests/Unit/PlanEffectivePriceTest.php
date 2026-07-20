<?php

namespace Tests\Unit;

use App\Models\Plan;
use Tests\TestCase;

/**
 * El precio efectivo es la única fuente de verdad del cobro: si esta lógica falla,
 * se cobra de más o de menos.
 */
class PlanEffectivePriceTest extends TestCase
{
    private function plan(array $attributes = []): Plan
    {
        return new Plan(array_merge([
            'price_regular'  => 69900,
            'offer_price'    => 39900,
            'offer_ends_at'  => null,
            'billing_period' => 'yearly',
        ], $attributes));
    }

    public function test_oferta_sin_fecha_de_fin_esta_vigente(): void
    {
        $plan = $this->plan();

        $this->assertTrue($plan->isOfferActive());
        $this->assertSame(39900.0, $plan->effectivePrice());
        $this->assertSame(43, $plan->discountPercent());
    }

    public function test_oferta_con_fecha_futura_esta_vigente(): void
    {
        $plan = $this->plan(['offer_ends_at' => now()->addDay()]);

        $this->assertTrue($plan->isOfferActive());
        $this->assertSame(39900.0, $plan->effectivePrice());
    }

    public function test_oferta_vencida_vuelve_al_precio_regular(): void
    {
        $plan = $this->plan(['offer_ends_at' => now()->subMinute()]);

        $this->assertFalse($plan->isOfferActive());
        $this->assertSame(69900.0, $plan->effectivePrice());
        $this->assertSame(0, $plan->discountPercent());
    }

    public function test_sin_precio_de_oferta_no_hay_descuento(): void
    {
        $plan = $this->plan(['offer_price' => null]);

        $this->assertFalse($plan->isOfferActive());
        $this->assertSame(69900.0, $plan->effectivePrice());
        $this->assertSame(0, $plan->discountPercent());
    }

    public function test_precio_efectivo_es_float_no_string(): void
    {
        // `decimal:2` serializa como string; si se filtra al JSON, el front concatena
        // en vez de sumar.
        $this->assertIsFloat($this->plan()->effectivePrice());
        $this->assertIsFloat($this->plan()->getEffectivePriceAttribute());
    }

    public function test_periodo_anual_suma_un_ano(): void
    {
        $start = now();
        $plan  = $this->plan(['billing_period' => 'yearly']);

        $this->assertSame(
            $start->copy()->addYear()->toDateString(),
            $plan->periodEnd($start)->toDateString()
        );
    }

    public function test_periodo_mensual_suma_un_mes(): void
    {
        $start = now();
        $plan  = $this->plan(['billing_period' => 'monthly']);

        $this->assertSame(
            $start->copy()->addMonth()->toDateString(),
            $plan->periodEnd($start)->toDateString()
        );
    }

    public function test_period_end_no_muta_la_fecha_recibida(): void
    {
        $start    = now();
        $original = $start->toDateTimeString();

        $this->plan()->periodEnd($start);

        $this->assertSame($original, $start->toDateTimeString());
    }
}
