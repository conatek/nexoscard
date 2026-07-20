<?php

use App\Models\Company;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas de tarjetas digitales (antes del catch-all)
|--------------------------------------------------------------------------
|
| Las rutas /{companySlug}/{cardSlug} se interceptan para inyectar
| OG meta tags (WhatsApp, Facebook, etc.) antes de servir la SPA.
|
*/

// OG tags para tarjeta individual: /{companySlug}/{cardSlug}
Route::get('/{companySlug}/{cardSlug}', function (string $companySlug, string $cardSlug) {
    $company = Company::where('slug', $companySlug)->first();

    // Sin suscripción vigente no se emiten OG tags: si no, WhatsApp y Facebook seguirían
    // mostrando nombre, cargo y foto de una tarjeta que ya está fuera de línea.
    if ($company && $company->hasPublicAccess()) {
        $card = $company->cards()
            ->where('slug', $cardSlug)
            ->where('is_active', true)
            ->first();

        if ($card) {
            $image = $card->thumbnail_path ?? $card->photo_path;

            // Transformar a 1200x630 con padding blanco para WhatsApp
            if ($image) {
                $image = str_replace(
                    '/image/upload/',
                    '/image/upload/w_1200,h_630,c_pad,b_white/',
                    $image
                );
            }

            return view('app', [
                'ogTitle'       => $card->full_name,
                'ogDescription' => trim(
                    ($card->job_title ? $card->job_title . ' — ' : '')
                    . $company->name
                ),
                'ogImage'       => $image,
                'ogUrl'         => url("{$companySlug}/{$cardSlug}"),
            ]);
        }
    }

    return view('app');
})->where(['companySlug' => '[a-z0-9\-_]+', 'cardSlug' => '[a-z0-9\-_]+']);

// Catch-all para la SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
