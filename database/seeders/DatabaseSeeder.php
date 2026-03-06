<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CompanyTypeSeeder::class,
            IndustrySeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            CompanySeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RoleHasPermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
