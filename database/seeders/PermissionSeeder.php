<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Dashboard
            'home_master',
            'home_super',
            'home_admin',
            'home_generic',

            // Permisos
            'permission_index',
            'permission_create',
            'permission_show',
            'permission_edit',
            'permission_destroy',

            // Roles
            'role_index',
            'role_create',
            'role_show',
            'role_edit',
            'role_destroy',

            // Usuarios
            'user_index',
            'user_create',
            'user_show',
            'user_edit',
            'user_destroy',

            // Empresas
            'company_index',
            'company_create',
            'company_show',
            'company_edit',
            'company_destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
               'name' => $permission,
            ]);
        }
    }
}
