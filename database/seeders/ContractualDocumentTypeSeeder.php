<?php

namespace Database\Seeders;

use App\Models\ContractualDocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractualDocumentTypeSeeder extends Seeder
{
    public function run()
    {
        ContractualDocumentType::create(['name' => 'Contrato de trabajo', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Documento de identificación', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Hoja de vida', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Certificado de procuraduría', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Certificado de policía', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Certificado de cuenta bancaria', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Certificado de EPS', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Certificado de fondo de pensiones', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Certificado de fondo de cesantías', 'observations' => null]);
        ContractualDocumentType::create(['name' => 'Otros', 'observations' => null]);
    }
}
