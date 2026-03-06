<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'first_surname',
        'second_surname',
        'document_type_id',
        'document_number',
        'document_province_id',
        'document_city_id',
        'expedition_date',
        'birth_province_id',
        'birth_city_id',
        'birth_date',
        'civil_status_type_id',
        'sex_type_id',
        'rh_type_id',
        'scholarship_type_id',
        'observations',
        'residence_province_id',
        'residence_city_id',
        'housing_tenure_id',
        'stratum_type_id',
        'address',
        'phone',
        'cellphone',
        'email',
        'image_public_id',
        'image_url',
    ];

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function document_province()
    {
        return $this->belongsTo(Province::class);
    }
}
