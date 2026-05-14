<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Merchant extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'merchants';

    /**
     * The attributes that are mass assignable.
     * Menjaga integritas data dengan allowed fields.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'store_name',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relasi ke categories.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relasi ke products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
