<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaboratorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'document_type_id',
        'document_public_id',
        'document_url',
        'observations'
    ];
}
