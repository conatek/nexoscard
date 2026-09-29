<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;

class PlanController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::active()->orderByDesc('is_default')->orderBy('sort_order')->get();

        return response()->json($plans);
    }

    /**
     * Detalle de un plan. Evita que el checkout tenga que traer todos los planes y
     * filtrar en el cliente.
     */
    public function show(Plan $plan): JsonResponse
    {
        abort_unless($plan->is_active, 404);

        return response()->json($plan);
    }

    /**
     * Precio del plan por defecto, días de prueba, contacto de soporte y guía de usuario para la landing
     * pública. Todo se edita desde el admin, así que no debe quedar fijo en el front.
     */
    public function landing(): JsonResponse
    {
        $plan = Plan::default();

        return response()->json([
            'plan' => $plan ? [
                'display_name'     => $plan->display_name,
                'price_regular'    => (float) $plan->price_regular,
                'effective_price'  => $plan->effectivePrice(),
                'is_offer_active'  => $plan->isOfferActive(),
                'discount_percent' => $plan->discountPercent(),
            ] : null,
            'trial_days' => AppSetting::getTrialDays(),
            'contact'    => AppSetting::publicContact(),
            'user_guide_url' => config('mail.user_guide_url'),
        ]);
    }
}
