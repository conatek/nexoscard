<?php

namespace App\Imports;

use App\Models\Collaborator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CollaboratorsImport implements ToCollection
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Collaborator::create([
                'company_id' => $row[0],
                'document_type_id' => $row[1],
                'document_number' => $row[2],
                'expedition_date' => $row[3],
                'document_province_id' => $row[4],
                'document_city_id' => $row[5],
                'name' => $row[6],
                'first_surname' => $row[7],
                'second_surname' => $row[8],
                'civil_status_type_id' => $row[9],
                'sex_type_id' => $row[10],
                'rh_type_id' => $row[11],
                'birth_date' => $row[12],
                'birth_province_id' => $row[13],
                'birth_city_id' => $row[14],
                'residence_province_id' => $row[15],
                'residence_city_id' => $row[16],
                'address' => $row[17],
                'housing_tenure_id' => $row[18],
                'stratum_type_id' => $row[19],
                'phone' => $row[20],
                'cellphone' => $row[21],
                'email' => $row[22],
                'image_public_id' => $row[23],
                'image_url' => $row[24],
                'observations' => $row[25],
            ]);
        }
    }
}
