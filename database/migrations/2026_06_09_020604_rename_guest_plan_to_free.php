<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')->where('name', 'guest')->update(['name' => 'free']);

        // Cambiar usuarios Guest a Admin
        $guestRole = DB::table('roles')->where('name', 'Guest')->first();
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();

        if ($guestRole && $adminRole) {
            DB::table('model_has_roles')
                ->where('role_id', $guestRole->id)
                ->update(['role_id' => $adminRole->id]);
        }
    }

    public function down(): void
    {
        DB::table('plans')->where('name', 'free')->update(['name' => 'guest']);
    }
};
