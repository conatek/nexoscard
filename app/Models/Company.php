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
        'logo_public_id',
    ];

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

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
