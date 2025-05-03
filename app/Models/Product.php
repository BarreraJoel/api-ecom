<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'stock',
        'category_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'carts');
    }

    public function categories() {
        return $this->hasOne(Category::class);
    }
    
}
