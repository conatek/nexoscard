<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Relationship::create(['name' => 'Cónyuge', 'type' => 'Afinidad', 'degree' => 1]);

        Relationship::create(['name' => 'Hijo(a)', 'type' => 'Consanguinidad', 'degree' => 1]);
        Relationship::create(['name' => 'Padre', 'type' => 'Consanguinidad', 'degree' => 1]);
        Relationship::create(['name' => 'Madre', 'type' => 'Consanguinidad', 'degree' => 1]);

        Relationship::create(['name' => 'Abuelo(a)', 'type' => 'Consanguinidad', 'degree' => 2]);
        Relationship::create(['name' => 'Nieto(a)', 'type' => 'Consanguinidad', 'degree' => 2]);
        Relationship::create(['name' => 'Hermano(a)', 'type' => 'Consanguinidad', 'degree' => 2]);

        Relationship::create(['name' => 'Bisabuelo(a)', 'type' => 'Consanguinidad', 'degree' => 3]);
        Relationship::create(['name' => 'Bisnieto(a)', 'type' => 'Consanguinidad', 'degree' => 3]);
        Relationship::create(['name' => 'Tío(a)', 'type' => 'Consanguinidad', 'degree' => 3]);
        Relationship::create(['name' => 'Sobrino(a)', 'type' => 'Consanguinidad', 'degree' => 3]);

        Relationship::create(['name' => 'Tatarabuelo(a)', 'type' => 'Consanguinidad', 'degree' => 4]);
        Relationship::create(['name' => 'Tataranieto(a)', 'type' => 'Consanguinidad', 'degree' => 4]);
        Relationship::create(['name' => 'Primo(a)', 'type' => 'Consanguinidad', 'degree' => 4]);

        Relationship::create(['name' => 'Suegro(a)', 'type' => 'Afinidad', 'degree' => 1]);
        Relationship::create(['name' => 'Yerno', 'type' => 'Afinidad', 'degree' => 1]);
        Relationship::create(['name' => 'Nuera', 'type' => 'Afinidad', 'degree' => 1]);

        Relationship::create(['name' => 'Abuelo(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 2]);
        Relationship::create(['name' => 'Hermano(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 2]);

        Relationship::create(['name' => 'Sobrino(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 3]);
        Relationship::create(['name' => 'Tío(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 3]);
        Relationship::create(['name' => 'Bisabuelo(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 3]);

        Relationship::create(['name' => 'Primo(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 4]);
        Relationship::create(['name' => 'Tatarabuelo(a) del cónyuge', 'type' => 'Afinidad', 'degree' => 4]);
    }
}
