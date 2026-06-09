<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Credenciales MercadoPago
    |--------------------------------------------------------------------------
    */
    'access_token'   => env('MERCADOPAGO_ACCESS_TOKEN'),
    'public_key'     => env('MERCADOPAGO_PUBLIC_KEY'),
    'webhook_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Moneda y pais
    |--------------------------------------------------------------------------
    */
    'currency' => env('MERCADOPAGO_CURRENCY', 'COP'),
    'country'  => env('MERCADOPAGO_COUNTRY', 'CO'),

    /*
    |--------------------------------------------------------------------------
    | URL de notificacion (webhook)
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('MERCADOPAGO_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | URLs de retorno
    |--------------------------------------------------------------------------
    */
    'back_urls' => [
        'success' => env('MERCADOPAGO_SUCCESS_URL'),
        'failure' => env('MERCADOPAGO_FAILURE_URL'),
        'pending' => env('MERCADOPAGO_PENDING_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox: true en desarrollo, false en produccion
    |--------------------------------------------------------------------------
    */
    'sandbox' => env('MERCADOPAGO_SANDBOX', true),

];
