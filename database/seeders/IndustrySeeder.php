<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run()
    {
        Industry::create(['name' => 'Agricultura, Silvicultura y Pesca']);
        Industry::create(['name' => 'Minería y Extracción']);
        Industry::create(['name' => 'Manufactura']);
        Industry::create(['name' => 'Construcción']);
        Industry::create(['name' => 'Comercio al por mayor y al por menor']);
        Industry::create(['name' => 'Servicios Financieros y Seguros']);
        Industry::create(['name' => 'Información y Tecnología']);
        Industry::create(['name' => 'Salud y Asistencia Social']);
        Industry::create(['name' => 'Transporte y Almacenamiento']);
        Industry::create(['name' => 'Alojamiento y Servicios de Comida']);
        Industry::create(['name' => 'Otro']);
    }
}
