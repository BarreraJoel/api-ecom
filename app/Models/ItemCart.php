<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCart extends Model
{
    protected $fillable =  [
        'user_id',
        'product_id',
        'quantity',
        'unit_price'
    ];
    
}
