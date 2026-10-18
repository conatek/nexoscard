<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'image_path',
        'price',
        'discount',
        'comment',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        // float y no decimal:2: el decimal llega al front como el texto "0.00", que en JS es
        // verdadero, y las vistas mostraban «-0.00%» o un precio tachado sin haber descuento.
        'discount'  => 'float',
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    // Precio con descuento aplicado
    public function getFinalPriceAttribute(): float
    {
        return round($this->price * (1 - $this->discount / 100), 2);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
