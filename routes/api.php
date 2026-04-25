<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PublicCardController;

// Rutas públicas de autenticación
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas públicas de tarjetas (sin auth)
Route::prefix('public')->group(function () {
    Route::get('/{companySlug}',             [PublicCardController::class, 'company']);
    Route::get('/{companySlug}/{cardSlug}',  [PublicCardController::class, 'card']);
});

// Rutas públicas de plantillas (sin auth - para consultar schemas)
Route::get('/templates', [CompanySettingController::class, 'templates']);
Route::get('/templates/{templateName}/schema', [CompanySettingController::class, 'schema']);

// Rutas protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/logout-all', [AuthController::class, 'logoutAll']);

    // Empresas (CRUD)
    Route::apiResource('companies', CompanyController::class);
    Route::get('companies/{company}/theme', [CompanyController::class, 'theme']);

    // Configuración de plantilla por empresa
    Route::get('companies/{company}/settings', [CompanySettingController::class, 'show']);
    Route::put('companies/{company}/settings', [CompanySettingController::class, 'update']);
    Route::post('companies/{company}/settings/reset', [CompanySettingController::class, 'reset']);
    Route::post('companies/{company}/settings/upload-image', [CompanySettingController::class, 'uploadImage']);

    // Tarjetas (anidadas bajo empresa)
    Route::apiResource('companies.cards', CardController::class);

    // Servicios (anidados bajo empresa)
    Route::apiResource('companies.services', ServiceController::class);

    // Productos (anidados bajo empresa)
    Route::apiResource('companies.products', ProductController::class);
});
