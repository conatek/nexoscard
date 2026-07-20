<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Saca de línea las tarjetas públicas de empresas sin suscripción vigente.
 *
 * Es el incentivo real de conversión: hasta ahora una tarjeta seguía publicada para
 * siempre aunque el trial hubiera vencido.
 *
 * Solo cubre `/api/public/*`. El panel del dueño (`/api/companies/*`) queda intacto a
 * propósito: con la suscripción vencida debe poder seguir entrando, editar su tarjeta y
 * pagar para reactivarla.
 */
class EnsureActiveSubscriptionForPublic
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('companySlug');

        if (!$slug) {
            return $next($request);
        }

        $company = Company::where('slug', $slug)->first();

        // Si la empresa no existe, que responda el controlador con su 404 habitual:
        // no conviene revelar aquí la diferencia entre "no existe" y "no está al día".
        if (!$company) {
            return $next($request);
        }

        if (!$company->hasPublicAccess()) {
            return response()->json([
                'available'    => false,
                'reason'       => 'subscription_expired',
                'company_name' => $company->name,
                'message'      => 'Esta tarjeta no está disponible en este momento.',
            ], 402);
        }

        return $next($request);
    }
}
