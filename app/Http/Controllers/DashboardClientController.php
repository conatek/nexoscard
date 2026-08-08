<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardClientController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user    = $request->user();
        $company = $user->company;

        if (! $company) {
            return response()->json([
                'user'         => $user->only('id', 'name', 'email'),
                'company'      => null,
                'subscription' => null,
                'stats'        => null,
                'contact'      => AppSetting::publicContact(),
            ]);
        }

        $company->loadCount(['cards', 'services', 'products']);
        $firstCard = $company->cards()->where('is_active', true)->first();
        // La más reciente aunque esté vencida: el panel de cuenta muestra el estado y el
        // CTA de activar, que es justo lo que necesita quien ya se venció.
        $subscription = $company->latestSubscription();
        $subscription?->load('plan');

        $plan = $subscription?->plan;

        return response()->json([
            'user'    => $user->only('id', 'name', 'email'),
            'company' => [
                'id'   => $company->id,
                'name' => $company->name,
                'slug' => $company->slug,
                'logo' => $company->logo_path,
                'first_card_slug' => $firstCard?->slug,
            ],
            'subscription' => $subscription ? [
                'status'         => $subscription->status,
                'plan_name'      => $plan?->display_name,
                'days_remaining' => $subscription->daysRemaining(),
                'period_end'     => $subscription->current_period_end,
                // Para el CTA de activar: el precio vigente lo resuelve el plan.
                'plan_id'         => $plan?->id,
                'plan_price'      => $plan?->effectivePrice(),
                'plan_period'     => $plan?->billing_period,
                'is_offer_active' => $plan?->isOfferActive(),
            ] : null,
            'stats' => [
                'cards'    => ['current' => $company->cards_count, 'limit' => $plan?->max_cards],
                'products' => ['current' => $company->products_count, 'limit' => $plan?->max_products],
                'services' => ['current' => $company->services_count, 'limit' => $plan?->max_services],
            ],
            // Canales de soporte, editables por el Master: la UI no debe hardcodearlos.
            'contact' => AppSetting::publicContact(),
        ]);
    }
}
