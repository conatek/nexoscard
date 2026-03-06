<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Master [1]
        $master_permissions = Permission::all()->filter(function($permission) {
            return $permission->name != 'home_super' &&
                $permission->name != 'home_admin' &&
                $permission->name != 'home_generic';
        });
        Role::findOrFail(1)->permissions()->sync($master_permissions->pluck('id'));

        // Super [2]
        $super_permissions = Permission::all()->filter(function ($permission) {
            return $permission->name != 'home_master' &&
                $permission->name != 'home_admin' &&
                $permission->name != 'home_generic' &&
                substr($permission->name, 0,5) != 'role_' &&
                substr($permission->name, 0,11) != 'permission_';
        });
        Role::findOrFail(2)->permissions()->sync($super_permissions);

        // Admin [3]
        $admin_permissions = Permission::all()->filter(function ($permission) {
            return $permission->name != 'home_master' &&
                $permission->name != 'home_super' &&
                $permission->name != 'home_generic' &&
                substr($permission->name, 0,5) != 'role_' &&
                substr($permission->name, 0,11) != 'permission_' &&
                $permission->name != 'user_index' &&
                $permission->name != 'user_create' &&
                $permission->name != 'user_show' &&
                $permission->name != 'user_edit' &&
                $permission->name != 'user_destroy';
        });
        Role::findOrFail(3)->permissions()->sync($admin_permissions);

        // Editor [4]
        $editor_permissions = $admin_permissions;
        Role::findOrFail(4)->permissions()->sync($editor_permissions);

        // Analyst [5]
        $analyst_permissions = $admin_permissions;
        Role::findOrFail(5)->permissions()->sync($analyst_permissions);

        // Collaborator [6]
        $collaborator_permissions = $admin_permissions;
        Role::findOrFail(6)->permissions()->sync($collaborator_permissions);

        // Guest [7]
        $guest_permissions = Permission::all()->filter(function ($permission) {
            return $permission->name != 'home_master' &&
                $permission->name != 'home_super' &&
                $permission->name != 'home_admin' &&
                substr($permission->name, 0,5) != 'role_' &&
                substr($permission->name, 0,11) != 'permission_' &&
                substr($permission->name, 0,5) != 'user_' &&
                substr($permission->name, 0,8) != 'company_' &&
                substr($permission->name, 0,5) != 'area_' &&
                substr($permission->name, 0,9) != 'position_' &&
                substr($permission->name, 0,7) != 'campus_';
        });
        Role::findOrFail(7)->permissions()->sync($guest_permissions);
    }
}
