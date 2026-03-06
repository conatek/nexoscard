<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    public function run()
    {
        // Master — todos los permisos excepto home_super, home_admin, home_generic
        $master_permissions = Permission::all()->filter(function ($permission) {
            return !in_array($permission->name, ['home_super', 'home_admin', 'home_generic']);
        });
        Role::findByName('Master')->permissions()->sync($master_permissions->pluck('id'));

        // Super — todo excepto home_master, home_admin, home_generic, role_*, permission_*
        $super_permissions = Permission::all()->filter(function ($permission) {
            return !in_array($permission->name, ['home_master', 'home_admin', 'home_generic'])
                && !str_starts_with($permission->name, 'role_')
                && !str_starts_with($permission->name, 'permission_');
        });
        Role::findByName('Super')->permissions()->sync($super_permissions->pluck('id'));

        // Admin — todo excepto home_master, home_super, home_generic, role_*, permission_*, user_*
        $admin_permissions = Permission::all()->filter(function ($permission) {
            return !in_array($permission->name, ['home_master', 'home_super', 'home_generic'])
                && !str_starts_with($permission->name, 'role_')
                && !str_starts_with($permission->name, 'permission_')
                && !str_starts_with($permission->name, 'user_');
        });
        Role::findByName('Admin')->permissions()->sync($admin_permissions->pluck('id'));

        // Editor, Analyst, User — mismos permisos que Admin
        foreach (['Editor', 'Analyst', 'User'] as $roleName) {
            Role::findByName($roleName)->permissions()->sync($admin_permissions->pluck('id'));
        }

        // Guest — solo home_generic
        $guest_permissions = Permission::where('name', 'home_generic')->get();
        Role::findByName('Guest')->permissions()->sync($guest_permissions->pluck('id'));
    }
}
