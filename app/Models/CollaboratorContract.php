<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollaboratorContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'collaborator_id',
        'position_id',
        'salary',
        'contract_type_id',
        'contract_start_date',
        'contract_end_date',
        'test_period_end_date',
        'corporate_email',
        'corporate_cellphone',
        'bank_id',
        'bank_account',
        'eps_id',
        'afp_pension_id',
        'afp_saving_id',
        'arl_id',
        'ccf_id',
        'created_at',
        'updated_at',
    ];
}
