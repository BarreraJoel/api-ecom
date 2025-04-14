<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'stock'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts');
    }
}
