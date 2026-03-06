<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaboratorFamily extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'name',
        'first_surname',
        'second_surname',
        'relationship_id',
        'sex_type_id',
        'occupation_id',
        'birth_date'
    ];
}
