<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
        'area_id',
        'criticality_level_id',
        'risk_class_id',
        'estimated_salary',
        'description',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
