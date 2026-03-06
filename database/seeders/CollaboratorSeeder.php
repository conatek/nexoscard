<?php

namespace Database\Seeders;

use App\Models\Collaborator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollaboratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Collaborator::factory()
                    ->count(50)
                    ->create();
    }
}
