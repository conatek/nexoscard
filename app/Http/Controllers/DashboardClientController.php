<?php

namespace App\Http\Controllers;

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
            ]);
        }

        $company->loadCount(['cards', 'services', 'products']);
        $firstCard = $company->cards()->where('is_active', true)->first();
        $subscription = $company->activeSubscription();
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
            ] : null,
            'stats' => [
                'cards'    => ['current' => $company->cards_count, 'limit' => $plan?->max_cards],
                'products' => ['current' => $company->products_count, 'limit' => $plan?->max_products],
                'services' => ['current' => $company->services_count, 'limit' => $plan?->max_services],
            ],
        ]);
    }
}
