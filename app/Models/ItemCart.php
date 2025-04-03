<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCart extends Model
{
    protected $fillable =  [
        'item_id',
        'quantity'
    ];
}
