<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Company;
use App\Models\CompanySetting;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Service;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Negocios de ejemplo para el video «Así funciona NexosCard». SOLO PARA EL ENTORNO LOCAL.
 *
 *   php artisan db:seed --class=VideoDemoSeeder
 *
 * Es idempotente: borra y recrea las 12 empresas por su slug. Las imágenes viven en
 * public/images/demo-video/ (fuera de git); sin ellas las tarjetas salen sin foto ni logo.
 * La protagonista es Camila Rojas (Trigo & Miel); el resto sale en el mosaico del video.
 * Clave de todas las cuentas: demo1234.
 */
class VideoDemoSeeder extends Seeder
{
    private const PASSWORD = 'demo1234';

    // Video de YouTube que ya usó la guía de usuario.
    private const VIDEO = 'https://youtu.be/sFSdkOnelU4';

    public function run(): void
    {
        if (app()->environment('production')) {
            $this->command?->error('VideoDemoSeeder es solo para el entorno local.');
            return;
        }

        $plan = Plan::default();
        if (!$plan) {
            $this->command?->error('No hay plan por defecto. Corre PlanSeeder primero.');
            return;
        }

        $base = rtrim(env('DEMO_ASSET_BASE', 'http://localhost:8001'), '/') . '/images/demo-video/';

        DB::transaction(function () use ($plan, $base) {
            foreach (self::negocios() as $n) {
                $this->borrar($n['slug'], $n['email']);
                $this->crear($n, $plan, $base);
            }
        });

        $this->command?->info(count(self::negocios()) . ' negocios de ejemplo creados (clave: ' . self::PASSWORD . ').');
    }

    private function borrar(string $slug, string $email): void
    {
        $company = Company::where('slug', $slug)->first();
        if ($company) {
            Card::where('company_id', $company->id)->delete();
            Product::where('company_id', $company->id)->delete();
            Service::where('company_id', $company->id)->delete();
            CompanySetting::where('company_id', $company->id)->delete();
            Subscription::where('company_id', $company->id)->delete();
        }
        User::where('email', $email)->delete();
        $company?->delete();
    }

    private function crear(array $n, Plan $plan, string $base): void
    {
        $company = Company::create([
            'name'      => $n['empresa'],
            'slug'      => $n['slug'],
            'logo_path' => $base . 'logo-' . $n['slug'] . '.png',
            'icon_path' => $base . 'logo-' . $n['slug'] . '.png',
            'address'   => $n['direccion'],
            'city'      => $n['ciudad'],
            'country'   => 'Colombia',
            'web'       => $n['web'] ?? null,
            'facebook'  => $n['facebook'] ?? null,
            'instagram' => $n['instagram'] ?? null,
            'youtube'   => $n['youtube'] ?? null,
            'tiktok'    => $n['tiktok'] ?? null,
        ]);

        $user = User::create([
            'company_id' => $company->id,
            'name'       => $n['nombre'] . ' ' . $n['apellido'],
            'email'      => $n['email'],
            'password'   => Hash::make(self::PASSWORD),
        ]);
        $user->assignRole('Admin');
        $company->update(['user_id' => $user->id]);

        // Prueba en curso: empezó hace $n['dias_usados'] días.
        $inicio = now()->subDays($n['dias_usados'] ?? 2);
        Subscription::create([
            'company_id'           => $company->id,
            'plan_id'              => $plan->id,
            'billing_period'       => 'yearly',
            'status'               => 'trial',
            'trial_ends_at'        => $inicio->copy()->addDays(7),
            'current_period_start' => $inicio,
            'current_period_end'   => $inicio->copy()->addDays(7),
        ]);

        CompanySetting::create([
            'company_id'    => $company->id,
            'template_name' => $n['plantilla'],
            'customization' => $n['ajustes'] ?? [],
        ]);

        Card::create([
            'company_id'       => $company->id,
            'first_name'       => $n['nombre'],
            'last_name'        => $n['apellido'],
            'slug'             => \Illuminate\Support\Str::slug($n['nombre'] . ' ' . $n['apellido']),
            'job_title'        => $n['cargo'],
            'photo_path'       => $base . 'foto-' . $n['foto'] . '.jpg',
            'thumbnail_path'   => $base . 'logo-' . $n['slug'] . '.png',
            'mobile_phone'     => $n['celular'],
            'whatsapp'         => '57' . preg_replace('/\D/', '', $n['celular']),
            'email'            => $n['email'],
            'whatsapp_message' => $n['mensaje'] ?? null,
            'description'      => '<p>' . $n['perfil'] . '</p>',
            'is_active'        => true,
        ]);

        foreach ($n['servicios'] ?? [] as $i => $s) {
            Service::create([
                'company_id'  => $company->id,
                'name'        => $s[0],
                'description' => '<p>' . $s[1] . '</p>',
                'image_path'  => isset($s[2]) ? $base . $s[2] . '.jpg' : null,
                'order'       => $i,
                'is_active'   => true,
            ]);
        }

        foreach ($n['productos'] ?? [] as $i => $p) {
            Product::create([
                'company_id'  => $company->id,
                'name'        => $p[0],
                'price'       => $p[1],
                'discount'    => $p[2],
                'description' => '<p>' . $p[3] . '</p>',
                'image_path'  => isset($p[4]) ? $base . $p[4] . '.jpg' : null,
                'order'       => $i,
                'is_active'   => true,
            ]);
        }
    }

    /** Impulso con el color de fondo y las luces del cristal tomados del logo. */
    private static function impulso(string $rubro, string $fondo, string $luz1, string $luz2, bool $video = false): array
    {
        return [
            'general' => ['colorFondo' => $fondo],
            'rubro'   => ['tipo' => $rubro],
            'glass'   => ['activar' => true, 'colorLuz1' => $luz1, 'colorLuz2' => $luz2],
            'video'   => ['mostrar' => $video, 'urlId' => $video ? self::VIDEO : ''],
        ];
    }

    public static function negocios(): array
    {
        return [
            // ── La protagonista ─────────────────────────────────────────
            [
                'slug' => 'trigo-y-miel', 'empresa' => 'Panadería Trigo & Miel',
                'nombre' => 'Camila', 'apellido' => 'Rojas', 'cargo' => 'Fundadora', 'foto' => 'f27',
                'email' => 'camila@trigoymiel.co', 'celular' => '310 482 1937',
                'direccion' => 'Calle 59 # 9-31, Chapinero', 'ciudad' => 'Bogotá',
                'web' => 'https://trigoymiel.co', 'instagram' => 'https://instagram.com/trigoymiel',
                'facebook' => 'https://facebook.com/trigoymiel', 'tiktok' => 'https://tiktok.com/@trigoymiel',
                'mensaje' => 'Hola Camila, quiero hacer un pedido.',
                'perfil' => 'Horneamos todos los días desde las 5 de la mañana, con masa madre y sin conservantes.',
                'plantilla' => 'impulso', 'dias_usados' => 2,
                'ajustes' => self::impulso('alimentos', '#3b1f0e', '#f59e0b', '#ec4899', true),
                'servicios' => [
                    ['Domicilios', 'Llevamos el pan caliente a tu casa u oficina en Chapinero y alrededores.', 'tm-domicilios'],
                    ['Pedidos para eventos', 'Tortas, bandejas de pasabocas y mesas de postres para cumpleaños, bautizos y reuniones de empresa.', 'tm-eventos'],
                    ['Desayunos sorpresa', 'Bandeja con café, pan recién horneado, fruta y una flor, con tarjeta escrita a mano.', 'tm-desayunos-sorpresa'],
                ],
                'productos' => [
                    ['Pan de bono', 2500, 0, 'Con queso costeño, horneado cada hora.', 'tm-pan-de-bono'],
                    ['Almojábana', 2800, 0, 'Suave por dentro, dorada por fuera.', 'tm-almojabana'],
                    ['Torta de tres leches', 48000, 10, 'Para 12 porciones. Pídela con un día de anticipación.', 'tm-torta-tres-leches'],
                    ['Croissant de mantequilla', 4500, 0, 'Hojaldre de 27 capas.', 'tm-croissant'],
                    ['Pan campesino', 9000, 0, 'Masa madre de 24 horas, 800 g.', 'tm-pan-campesino'],
                ],
            ],

            // ── Impulso, otros rubros ───────────────────────────────────
            [
                'slug' => 'altavista', 'empresa' => 'Altavista Inmobiliaria',
                'nombre' => 'Mateo', 'apellido' => 'Vélez', 'cargo' => 'Asesor comercial', 'foto' => 'm30',
                'email' => 'mateo@altavista.co', 'celular' => '315 207 6648',
                'direccion' => 'Carrera 43A # 1-50, El Poblado', 'ciudad' => 'Medellín',
                'web' => 'https://altavista.co', 'instagram' => 'https://instagram.com/altavista.co',
                'perfil' => 'Te acompaño a encontrar apartamento en El Poblado, Envigado y Sabaneta.',
                'plantilla' => 'impulso',
                'ajustes' => self::impulso('inmobiliarias', '#0b1f3a', '#14b8a6', '#38bdf8'),
                'servicios' => [
                    ['Venta de inmuebles', 'Apartamentos y casas nuevas y usadas.'],
                    ['Arriendos', 'Inmuebles con contrato y póliza.'],
                ],
            ],
            [
                'slug' => 'sonrie', 'empresa' => 'Clínica Dental Sonríe',
                'nombre' => 'Juliana', 'apellido' => 'Herrera', 'cargo' => 'Odontóloga', 'foto' => 'f34',
                'email' => 'juliana@sonrie.co', 'celular' => '317 590 2284',
                'direccion' => 'Calle 36 # 27-52, Cabecera', 'ciudad' => 'Bucaramanga',
                'instagram' => 'https://instagram.com/sonrie.bga',
                'perfil' => 'Odontología general y estética. Atiendo adultos y niños.',
                'plantilla' => 'impulso',
                'ajustes' => self::impulso('profesionales', '#1e1b4b', '#22d3ee', '#6366f1'),
                'servicios' => [
                    ['Limpieza dental', 'Profilaxis y control cada seis meses.'],
                    ['Diseño de sonrisa', 'Valoración sin costo.'],
                ],
            ],
            [
                'slug' => 'brisa-caribe', 'empresa' => 'Hotel Brisa Caribe',
                'nombre' => 'Natalia', 'apellido' => 'Pérez', 'cargo' => 'Gerente', 'foto' => 'f40',
                'email' => 'natalia@brisacaribe.co', 'celular' => '300 718 4402',
                'direccion' => 'Calle del Cuartel # 36-77, Centro Histórico', 'ciudad' => 'Cartagena',
                'web' => 'https://brisacaribe.co', 'instagram' => 'https://instagram.com/brisacaribe',
                'perfil' => 'Doce habitaciones en una casa colonial a dos cuadras de la Torre del Reloj.',
                'plantilla' => 'impulso',
                'ajustes' => self::impulso('hospedajes', '#7c2d12', '#ff7e5f', '#0e7490'),
                'servicios' => [
                    ['Habitaciones', 'Desayuno incluido.'],
                    ['Tours por la ciudad', 'Recorridos a pie y en chiva.'],
                ],
            ],
            [
                'slug' => 'flores-del-valle', 'empresa' => 'Flores del Valle',
                'nombre' => 'Gloria', 'apellido' => 'Muñoz', 'cargo' => 'Propietaria', 'foto' => 'f65',
                'email' => 'gloria@floresdelvalle.co', 'celular' => '318 344 9015',
                'direccion' => 'Avenida 6 Norte # 23-41, Granada', 'ciudad' => 'Cali',
                'instagram' => 'https://instagram.com/floresdelvalle',
                'perfil' => 'Arreglos florales para toda ocasión, con entrega el mismo día.',
                'plantilla' => 'impulso',
                'ajustes' => self::impulso('comerciantes', '#881337', '#fb7185', '#fbbf24'),
            ],
            [
                'slug' => 'el-patron', 'empresa' => 'Barbería El Patrón',
                'nombre' => 'Julián', 'apellido' => 'Arango', 'cargo' => 'Barbero', 'foto' => 'm45',
                'email' => 'julian@elpatron.co', 'celular' => '311 650 7723',
                'direccion' => 'Carrera 70 # 44-12, Laureles', 'ciudad' => 'Medellín',
                'instagram' => 'https://instagram.com/barberiaelpatron',
                'perfil' => 'Corte, barba y toalla caliente. Con cita o por orden de llegada.',
                'plantilla' => 'impulso',
                'ajustes' => self::impulso('servicios', '#111111', '#c8a24a', '#7c3aed'),
            ],

            // ── Las otras seis plantillas ───────────────────────────────
            [
                'slug' => 'ruta-andina', 'empresa' => 'Ruta Andina Bicicletas',
                'nombre' => 'Andrés', 'apellido' => 'Restrepo', 'cargo' => 'Propietario', 'foto' => 'm42',
                'email' => 'andres@rutaandina.co', 'celular' => '316 902 5531',
                'direccion' => 'Carrera 23 # 62-15, Cable', 'ciudad' => 'Manizales',
                'perfil' => 'Venta, taller y alquiler de bicicletas de ruta y montaña.',
                'plantilla' => 'vibrant',
            ],
            [
                'slug' => 'huellitas', 'empresa' => 'Huellitas Pet Shop',
                'nombre' => 'Sebastián', 'apellido' => 'Mosquera', 'cargo' => 'Administrador', 'foto' => 'm47',
                'email' => 'sebastian@huellitas.co', 'celular' => '320 415 8890',
                'direccion' => 'Calle 5 # 38-20, San Fernando', 'ciudad' => 'Cali',
                'perfil' => 'Alimento, accesorios y peluquería para perros y gatos.',
                'plantilla' => 'creative',
            ],
            [
                'slug' => 'roble', 'empresa' => 'Constructora Roble',
                'nombre' => 'Jorge', 'apellido' => 'Ramírez', 'cargo' => 'Director de proyectos', 'foto' => 'm53',
                'email' => 'jorge@constructoraroble.co', 'celular' => '313 280 4417',
                'direccion' => 'Avenida Circunvalar # 12-60', 'ciudad' => 'Pereira',
                'perfil' => 'Vivienda y locales comerciales en el Eje Cafetero desde 2004.',
                'plantilla' => 'classic',
            ],
            [
                'slug' => 'cifra', 'empresa' => 'Cifra Contadores',
                'nombre' => 'Patricia', 'apellido' => 'Gómez', 'cargo' => 'Contadora pública', 'foto' => 'f49',
                'email' => 'patricia@cifra.co', 'celular' => '301 733 2096',
                'direccion' => 'Carrera 53 # 82-86, El Prado', 'ciudad' => 'Barranquilla',
                'perfil' => 'Contabilidad, nómina e impuestos para pymes y personas naturales.',
                'plantilla' => 'modern',
            ],
            [
                'slug' => 'tecnored', 'empresa' => 'TecnoRed Soluciones',
                'nombre' => 'Felipe', 'apellido' => 'Castaño', 'cargo' => 'Ingeniero de soporte', 'foto' => 'm28',
                'email' => 'felipe@tecnored.co', 'celular' => '312 564 1180',
                'direccion' => 'Calle 93 # 13-24', 'ciudad' => 'Bogotá',
                'perfil' => 'Redes, cámaras y soporte técnico para oficinas.',
                'plantilla' => 'cyber',
            ],
            [
                'slug' => 'raiz-yoga', 'empresa' => 'Raíz Yoga',
                'nombre' => 'Laura', 'apellido' => 'Benítez', 'cargo' => 'Instructora', 'foto' => 'f24',
                'email' => 'laura@raizyoga.co', 'celular' => '305 219 6674',
                'direccion' => 'Calle 22 # 2-18, El Rodadero', 'ciudad' => 'Santa Marta',
                'perfil' => 'Clases de yoga frente al mar, grupales y personalizadas.',
                'plantilla' => 'minimal',
            ],
        ];
    }
}
