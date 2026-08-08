<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Retorna la suscripción activa de la empresa del usuario,
     * junto con el plan, conteos de recursos y límites.
     */
    public function current(Request $request): JsonResponse
    {
        $user    = $request->user();
        $company = $user->company;

        if (!$company) {
            return response()->json(['subscription' => null]);
        }

        // La más reciente, no solo trial/active: el banner y "Mi Plan" tienen que poder
        // decir "vencido" y ofrecer renovar. Filtrar aquí dejaba a un cliente vencido
        // igual que a uno sin suscripción, sin mensaje ni botón de pago.
        $subscription = $company->latestSubscription();

        if (!$subscription) {
            return response()->json(['subscription' => null]);
        }

        $subscription->load('plan');
        $plan = $subscription->plan;

        return response()->json([
            'subscription'   => $subscription,
            'plan'           => $plan,
            'days_remaining' => $subscription->daysRemaining(),
            'usage'          => [
                'cards'    => [
                    'current' => $company->cards()->count(),
                    'limit'   => $plan->max_cards,
                ],
                'products' => [
                    'current' => $company->products()->count(),
                    'limit'   => $plan->max_products,
                ],
                'services' => [
                    'current' => $company->services()->count(),
                    'limit'   => $plan->max_services,
                ],
            ],
        ]);
    }
}
