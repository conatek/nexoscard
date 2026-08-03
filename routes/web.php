<?php

use App\Models\Card;
use App\Models\Company;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Rutas públicas de tarjetas digitales (antes del catch-all)
|--------------------------------------------------------------------------
|
| Las rutas /{companySlug}/{cardSlug} se interceptan para inyectar
| OG meta tags (WhatsApp, Facebook, etc.) antes de servir la SPA.
|
*/

/**
 * Tarjeta publicada, o null si no existe o la empresa no tiene suscripción vigente.
 *
 * Sin esa comprobación, tanto los OG tags como el manifest seguirían exponiendo nombre,
 * cargo y foto de una tarjeta que ya está fuera de línea.
 *
 * @return array{0: Company, 1: Card}|null
 */
$findPublicCard = function (string $companySlug, string $cardSlug): ?array {
    $company = Company::where('slug', $companySlug)->first();

    if (!$company || !$company->hasPublicAccess()) {
        return null;
    }

    $card = $company->cards()
        ->where('slug', $cardSlug)
        ->where('is_active', true)
        ->first();

    return $card ? [$company, $card] : null;
};

/*
 * Manifest por tarjeta. Define el icono y el nombre del acceso directo en Android, y hace
 * que al abrirlo se entre en la tarjeta: el manifest global apunta a /login, así que hasta
 * ahora el acceso directo de una tarjeta abría el login de NexosCard.
 */
Route::get('/{companySlug}/{cardSlug}/manifest.webmanifest', function (
    string $companySlug,
    string $cardSlug
) use ($findPublicCard) {
    $found = $findPublicCard($companySlug, $cardSlug);

    abort_if(!$found, 404);

    [$company, $card] = $found;

    $url = url("{$companySlug}/{$cardSlug}");

    $icons = [];
    foreach ([192, 512] as $size) {
        $icons[] = [
            'src'   => $company->shortcutIconUrl($size) ?? asset("pwa-{$size}x{$size}.png"),
            'sizes' => "{$size}x{$size}",
            'type'  => 'image/png',
        ];
    }

    // Los iconos no se declaran "maskable": ya llevan su propio relleno, y Android
    // recortaría un círculo sobre ellos comiéndose los bordes del logo del cliente.
    return response()->json([
        'id'               => $url,
        'name'             => $card->full_name,
        'short_name'       => Str::limit($card->first_name ?: $card->full_name, 12, ''),
        'description'      => trim(($card->job_title ? $card->job_title . ' — ' : '') . $company->name),
        'start_url'        => $url,
        'scope'            => $url,
        'display'          => 'standalone',
        'background_color' => '#ffffff',
        'theme_color'      => '#7c3aed',
        'icons'            => $icons,
    ])->header('Content-Type', 'application/manifest+json');
})->where(['companySlug' => '[a-z0-9\-_]+', 'cardSlug' => '[a-z0-9\-_]+']);

// OG tags para tarjeta individual: /{companySlug}/{cardSlug}
Route::get('/{companySlug}/{cardSlug}', function (
    string $companySlug,
    string $cardSlug
) use ($findPublicCard) {
    $found = $findPublicCard($companySlug, $cardSlug);

    if ($found) {
        [$company, $card] = $found;

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

            // Acceso directo que el visitante guarda en su dispositivo: iOS ignora el
            // manifest y usa apple-touch-icon, Android usa el manifest.
            'manifestUrl'    => url("{$companySlug}/{$cardSlug}/manifest.webmanifest"),
            'appleTouchIcon' => $company->shortcutIconUrl(180),
            'shortcutTitle'  => $card->full_name,
        ]);
    }

    return view('app');
})->where(['companySlug' => '[a-z0-9\-_]+', 'cardSlug' => '[a-z0-9\-_]+']);

// Catch-all para la SPA
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
