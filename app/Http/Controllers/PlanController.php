<?php

namespace App\Http\Controllers;

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
}
