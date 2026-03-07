<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'logo_path',
        'address',
        'address_notes',
        'phone',
        'email',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'website',
        'description',
        'design_settings',
    ];

    protected $casts = [
        'design_settings' => 'array',
    ];

    // Estructura por defecto del JSON de diseño
    public function getDesignSettingsAttribute(?string $value): array
    {
        $defaults = [
            'template'         => 'modern',
            'primary_color'    => '#3B82F6',
            'secondary_color'  => '#1E40AF',
            'background_color' => '#FFFFFF',
            'text_color'       => '#111827',
            'accent_color'     => '#F59E0B',
            'font_family'      => 'Inter',
            'border_radius'    => 8,
            'show_services'    => true,
            'show_products'    => true,
        ];

        if (is_null($value)) {
            return $defaults;
        }

        return array_merge($defaults, json_decode($value, true) ?? []);
    }

    // --- Relaciones ---

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class)->orderBy('order');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('order');
    }

    // El User que pertenece a esta empresa (empleados)
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
