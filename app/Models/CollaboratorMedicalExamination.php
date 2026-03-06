<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaboratorMedicalExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'examination_type_id',
        'examination_date',
        'observations',
        'result_public_id',
        'result_url'
    ];
}
