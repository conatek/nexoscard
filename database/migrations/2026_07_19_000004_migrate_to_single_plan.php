<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Sustituye los planes free/basico/pro por el único producto "Presencia Digital".
 *
 * ORDEN CRÍTICO: `subscriptions.plan_id` tiene `restrictOnDelete()`, así que las
 * suscripciones vivas deben reasignarse ANTES de borrar los planes viejos o la BD
 * rechaza el DELETE.
 *
 * AVISO SOBRE down(): recrea los tres planes con sus valores originales, pero NO puede
 * restaurar qué empresa tenía cuál — esa información se pierde al reasignar. El down
 * sirve para dejar el esquema utilizable, no para revertir el negocio. Hay respaldo
 * previo en ~/nexoscard-backups/.
 */
return new class extends Migration
{
    private const LEGACY = ['free', 'basico', 'pro'];

    public function up(): void
    {
        DB::transaction(function () {
            $now = now();

            DB::table('plans')->updateOrInsert(
                ['name' => 'presencia-digital'],
                [
                    'display_name'        => 'Presencia Digital',
                    'price_regular'       => 69000,
                    'offer_price'         => 39000,
                    // Sin fecha: la oferta corre indefinida y sin contador hasta que el
                    // Master fije una desde el panel.
                    'offer_ends_at'       => null,
                    'billing_period'      => 'yearly',
                    'max_cards'           => 1,
                    'max_products'        => null,  // ilimitado
                    'max_services'        => null,  // ilimitado
                    'available_templates' => null,  // todas
                    'show_watermark'      => false,
                    'features'            => json_encode($this->features(), JSON_UNESCAPED_UNICODE),
                    'is_active'           => true,
                    'is_default'          => true,
                    'sort_order'          => 0,
                    'updated_at'          => $now,
                    'created_at'          => $now,
                ]
            );

            $newId = DB::table('plans')->where('name', 'presencia-digital')->value('id');

            $legacyIds = DB::table('plans')
                ->whereIn('name', self::LEGACY)
                ->pluck('id')
                ->all();

            if (empty($legacyIds)) {
                return;
            }

            // 1) Reasignar suscripciones (antes del delete, por el FK).
            DB::table('subscriptions')
                ->whereIn('plan_id', $legacyIds)
                ->update(['plan_id' => $newId, 'updated_at' => $now]);

            // 2) Repuntar el plan_id que vive dentro del JSON de metadata de los pagos.
            //    No hay FK, pero AdminPaymentDetail lo lee y quedaría mostrando un plan
            //    inexistente.
            foreach (DB::table('payments')->whereNotNull('metadata')->get() as $payment) {
                $meta = json_decode($payment->metadata, true);

                if (!is_array($meta) || !isset($meta['plan_id'])) {
                    continue;
                }

                if (!in_array((int) $meta['plan_id'], $legacyIds, true)) {
                    continue;
                }

                $meta['plan_id']          = $newId;
                $meta['legacy_plan_name'] = $meta['plan_name'] ?? null;
                $meta['plan_name']        = 'presencia-digital';

                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update(['metadata' => json_encode($meta, JSON_UNESCAPED_UNICODE)]);
            }

            // 3) Ahora sí, borrar los planes viejos.
            DB::table('plans')->whereIn('id', $legacyIds)->delete();
        });
    }

    public function down(): void
    {
        $now = now();

        DB::table('plans')->insertOrIgnore([
            [
                'name' => 'free', 'display_name' => 'Gratis',
                'price_regular' => 0, 'max_cards' => 1, 'max_products' => 3, 'max_services' => 3,
                'available_templates' => json_encode(['modern', 'classic']),
                'show_watermark' => true, 'features' => json_encode([]),
                'is_active' => true, 'sort_order' => 1,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'basico', 'display_name' => 'Básico',
                'price_regular' => 499000, 'max_cards' => 5, 'max_products' => 15, 'max_services' => 15,
                'available_templates' => null, 'show_watermark' => false,
                'features' => json_encode(['qr_personalizado' => true]),
                'is_active' => true, 'sort_order' => 2,
                'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'name' => 'pro', 'display_name' => 'Pro',
                'price_regular' => 999000, 'max_cards' => 20, 'max_products' => null, 'max_services' => null,
                'available_templates' => null, 'show_watermark' => false,
                'features' => json_encode(['qr_personalizado' => true, 'dominio_propio' => true]),
                'is_active' => true, 'sort_order' => 3,
                'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }

    /**
     * Bullets comerciales del PDF "Plan Inicial", como lista de strings.
     */
    private function features(): array
    {
        return [
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
};
