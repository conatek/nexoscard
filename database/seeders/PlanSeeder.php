<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Un único producto: "Presencia Digital". Los valores deben coincidir con los de la
 * migración 2026_07_19_000004_migrate_to_single_plan para que ambos caminos (instalación
 * nueva vía seeder, instalación existente vía migración) converjan al mismo plan.
 */
class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['name' => 'presencia-digital'],
            [
                'display_name'        => 'Presencia Digital',
                'price_regular'       => 69000,
                'offer_price'         => 39000,
                // Sin fecha: la oferta corre sin contador hasta que el Master fije una.
                'offer_ends_at'       => null,
                'billing_period'      => 'yearly',
                'max_cards'           => 1,
                'max_products'        => null,  // ilimitado
                'max_services'        => null,  // ilimitado
                'available_templates' => null,  // todas
                'show_watermark'      => false,
                'features'            => self::FEATURES,
                'is_active'           => true,
                'is_default'          => true,
                'sort_order'          => 0,
            ]
        );
    }

    /**
     * Bullets comerciales del PDF "Plan Inicial". Lista de strings: el front los pinta
     * tal cual en la landing de la oferta.
     */
    public const FEATURES = [
        '1 tarjeta digital interactiva personalizada',
        'Hosting para tu tarjeta durante la vigencia de la suscripción',
        'Foto de perfil o logo',
        'Información de contacto',
        'Integración con Google Maps',
        'Botón directo a WhatsApp',
        'Enlace a tus redes sociales',
        'Enlace a tu video de presentación',
        'Enlace a tu catálogo',
        'Compartir mediante enlace',
        'Código QR personalizado',
        'Diseño básico profesional',
        'Cambios ilimitados en la información e imágenes',
        'Actualización y soporte',
    ];
}
