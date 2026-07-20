<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoFlowSeeder extends Seeder
{
    public function run(): void
    {
        // Solo hay un producto: los 6 escenarios de estado corren todos sobre él.
        $plan = Plan::default();

        if (!$plan) {
            $this->command?->error('No hay plan por defecto. Corre PlanSeeder primero.');
            return;
        }

        $freePlan = $basicoPlan = $proPlan = $plan;

        // ─────────────────────────────────────────────────────────────
        // 1. TRIAL FRESCO (25 dias restantes)
        //    Para ver banner info "Periodo de prueba. Te quedan 25 dias."
        // ─────────────────────────────────────────────────────────────
        $company1 = Company::create([
            'name' => 'Demo Trial Fresco',
            'slug' => 'demo-trial-fresco',
        ]);
        $user1 = User::create([
            'company_id' => $company1->id,
            'name'       => 'Carlos Trial',
            'email'      => 'trial@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user1->assignRole('Admin');
        $company1->update(['user_id' => $user1->id]);

        Subscription::create([
            'company_id'           => $company1->id,
            'plan_id'              => $freePlan->id,
            'status'               => 'trial',
            'trial_ends_at'        => now()->addDays(25),
            'current_period_start' => now()->subDays(5),
            'current_period_end'   => now()->addDays(25),
        ]);

        // ─────────────────────────────────────────────────────────────
        // 2. TRIAL POR VENCER (7 dias)
        //    Para ver banner warning + probar email TrialExpiring
        // ─────────────────────────────────────────────────────────────
        $company2 = Company::create([
            'name' => 'Demo Trial Venciendo',
            'slug' => 'demo-trial-venciendo',
        ]);
        $user2 = User::create([
            'company_id' => $company2->id,
            'name'       => 'Laura Expiring',
            'email'      => 'expiring@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user2->assignRole('Admin');
        $company2->update(['user_id' => $user2->id]);

        Subscription::create([
            'company_id'           => $company2->id,
            'plan_id'              => $freePlan->id,
            'status'               => 'trial',
            'trial_ends_at'        => now()->addDays(7),
            'current_period_start' => now()->subDays(23),
            'current_period_end'   => now()->addDays(7),
        ]);

        // ─────────────────────────────────────────────────────────────
        // 3. TRIAL URGENTE (2 dias)
        //    Para ver banner urgent rojo
        // ─────────────────────────────────────────────────────────────
        $company3 = Company::create([
            'name' => 'Demo Trial Urgente',
            'slug' => 'demo-trial-urgente',
        ]);
        $user3 = User::create([
            'company_id' => $company3->id,
            'name'       => 'Pedro Urgente',
            'email'      => 'urgente@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user3->assignRole('Admin');
        $company3->update(['user_id' => $user3->id]);

        Subscription::create([
            'company_id'           => $company3->id,
            'plan_id'              => $freePlan->id,
            'status'               => 'trial',
            'trial_ends_at'        => now()->addDays(2),
            'current_period_start' => now()->subDays(28),
            'current_period_end'   => now()->addDays(2),
        ]);

        // ─────────────────────────────────────────────────────────────
        // 4. TRIAL EXPIRADO
        //    Para ver banner error + probar email TrialExpired
        // ─────────────────────────────────────────────────────────────
        $company4 = Company::create([
            'name' => 'Demo Trial Expirado',
            'slug' => 'demo-trial-expirado',
        ]);
        $user4 = User::create([
            'company_id' => $company4->id,
            'name'       => 'Ana Expired',
            'email'      => 'expired@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user4->assignRole('Admin');
        $company4->update(['user_id' => $user4->id]);

        Subscription::create([
            'company_id'           => $company4->id,
            'plan_id'              => $freePlan->id,
            'status'               => 'expired',
            'trial_ends_at'        => now()->subDays(5),
            'current_period_start' => now()->subDays(35),
            'current_period_end'   => now()->subDays(5),
        ]);

        // ─────────────────────────────────────────────────────────────
        // 5. SUSCRIPCION ACTIVA (Plan Basico)
        //    Para ver sin banner + validar funcionalidades
        // ─────────────────────────────────────────────────────────────
        $company5 = Company::create([
            'name' => 'Demo Plan Activo',
            'slug' => 'demo-plan-activo',
        ]);
        $user5 = User::create([
            'company_id' => $company5->id,
            'name'       => 'Maria Activa',
            'email'      => 'activo@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user5->assignRole('Admin');
        $company5->update(['user_id' => $user5->id]);

        $sub5 = Subscription::create([
            'company_id'           => $company5->id,
            'plan_id'              => $basicoPlan->id,
            'status'               => 'active',
            'payment_method'       => 'mercadopago',
            'current_period_start' => now()->subDays(10),
            'current_period_end'   => now()->addDays(20),
        ]);

        Payment::create([
            'subscription_id'        => $sub5->id,
            'company_id'             => $company5->id,
            'amount'                 => 49900,
            'currency'               => 'COP',
            'status'                 => 'approved',
            'payment_method'         => 'credit_card',
            'mercadopago_payment_id' => 'DEMO-001',
            'paid_at'                => now()->subDays(10),
            'metadata'               => ['plan_id' => $basicoPlan->id, 'billing_period' => 'yearly', 'plan_name' => 'Presencia Digital'],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 6. SUSCRIPCION PAST DUE (Plan Pro vencido hace 3 dias)
        //    Para ver banner warning "pago pendiente"
        // ─────────────────────────────────────────────────────────────
        $company6 = Company::create([
            'name' => 'Demo Past Due',
            'slug' => 'demo-past-due',
        ]);
        $user6 = User::create([
            'company_id' => $company6->id,
            'name'       => 'Jorge PastDue',
            'email'      => 'pastdue@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user6->assignRole('Admin');
        $company6->update(['user_id' => $user6->id]);

        $sub6 = Subscription::create([
            'company_id'           => $company6->id,
            'plan_id'              => $proPlan->id,
            'status'               => 'past_due',
            'payment_method'       => 'mercadopago',
            'current_period_start' => now()->subDays(33),
            'current_period_end'   => now()->subDays(3),
        ]);

        Payment::create([
            'subscription_id'        => $sub6->id,
            'company_id'             => $company6->id,
            'amount'                 => 99900,
            'currency'               => 'COP',
            'status'                 => 'approved',
            'payment_method'         => 'credit_card',
            'mercadopago_payment_id' => 'DEMO-002',
            'paid_at'                => now()->subDays(33),
            'metadata'               => ['plan_id' => $proPlan->id, 'billing_period' => 'yearly', 'plan_name' => 'Presencia Digital'],
        ]);

        // ─────────────────────────────────────────────────────────────
        // 7. USUARIO PARA CHECKOUT (Trial fresco, listo para pagar)
        //    Para probar flujo completo de pago con MercadoPago
        // ─────────────────────────────────────────────────────────────
        $company7 = Company::create([
            'name' => 'Demo Checkout',
            'slug' => 'demo-checkout',
        ]);
        $user7 = User::create([
            'company_id' => $company7->id,
            'name'       => 'Test Checkout',
            'email'      => 'checkout@demo.com',
            'password'   => Hash::make('demo1234'),
        ]);
        $user7->assignRole('Admin');
        $company7->update(['user_id' => $user7->id]);

        Subscription::create([
            'company_id'           => $company7->id,
            'plan_id'              => $freePlan->id,
            'status'               => 'trial',
            'trial_ends_at'        => now()->addDays(15),
            'current_period_start' => now()->subDays(15),
            'current_period_end'   => now()->addDays(15),
        ]);
    }
}
