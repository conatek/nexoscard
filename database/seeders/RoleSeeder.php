<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles
        $master = Role::firstOrCreate(['name' => 'Master', 'guard_name' => 'web']);
        $admin  = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        // Master: todos los permisos
        $master->syncPermissions(Permission::all());

        // Admin: todos excepto los exclusivos de Master
        $admin->syncPermissions(
            Permission::whereNotIn('name', [
                'view_any_company',
                'delete_company',
                'manage_users',
            ])->get()
        );
    }
}
