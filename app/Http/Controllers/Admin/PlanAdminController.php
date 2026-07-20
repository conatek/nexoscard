<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanAdminController extends Controller
{
    public function index(): JsonResponse
    {
        $plans = Plan::withCount(['subscriptions' => function ($q) {
            $q->whereIn('status', ['trial', 'active']);
        }])->orderByDesc('is_default')->orderBy('sort_order')->get();

        return response()->json($plans);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());

        $plan = DB::transaction(function () use ($data) {
            $plan = Plan::create($data);
            $this->ensureSingleDefault($plan);

            return $plan;
        });

        return response()->json($plan->fresh(), 201);
    }

    public function update(Request $request, Plan $plan): JsonResponse
    {
        $data = $request->validate($this->rules($plan));

        DB::transaction(function () use ($plan, $data) {
            $plan->update($data);
            $this->ensureSingleDefault($plan);
        });

        return response()->json($plan->fresh());
    }

    public function toggle(Plan $plan): JsonResponse
    {
        // Desactivar el plan por defecto dejaría a `createTrialSubscription()` sin plan
        // y rompería el registro de usuarios.
        if ($plan->is_active && $plan->is_default) {
            return response()->json([
                'message' => 'No puedes desactivar el plan por defecto. Marca antes otro plan como predeterminado.',
            ], 422);
        }

        $plan->update(['is_active' => !$plan->is_active]);

        return response()->json([
            'message'   => $plan->is_active ? 'Plan activado' : 'Plan desactivado',
            'is_active' => $plan->is_active,
        ]);
    }

    private function rules(?Plan $plan = null): array
    {
        $unique = 'unique:plans,name' . ($plan ? ',' . $plan->id : '');

        return [
            'name'                => 'required|string|max:50|' . $unique,
            'display_name'        => 'required|string|max:100',
            'price_regular'       => 'required|numeric|min:0',
            'offer_price'         => 'nullable|numeric|min:0|lte:price_regular',
            'offer_ends_at'       => 'nullable|date',
            'billing_period'      => 'required|in:monthly,yearly',
            'max_cards'           => 'required|integer|min:1',
            'max_products'        => 'nullable|integer|min:1',
            'max_services'        => 'nullable|integer|min:1',
            'available_templates' => 'nullable|array',
            'show_watermark'      => 'boolean',
            'features'            => 'nullable|array',
            'features.*'          => 'string|max:255',
            'is_active'           => 'boolean',
            'is_default'          => 'boolean',
            'sort_order'          => 'integer|min:0',
        ];
    }

    /**
     * Solo puede haber un plan por defecto: al marcar uno, se desmarcan los demás.
     */
    private function ensureSingleDefault(Plan $plan): void
    {
        if (!$plan->is_default) {
            return;
        }

        Plan::where('id', '!=', $plan->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }
}
