<?php

namespace App\Services;

use App\Models\ItemCart;
use App\Models\User;

class CartService
{
    public function __construct() {}

    public function getAll()
    {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }
        return $user->products;
    }

    public function add(ItemCart $itemCart)
    {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $modeluser = User::find($user->id);
        $modeluser->products()->attach((int)$itemCart->item_id, ['quantity' => (int)$itemCart->quantity]);
        return true;
    }

    public function remove($itemId)
    {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $modeluser = User::find($user->id);
        $modeluser->products()->detach($itemId);

        return true;
    }

    public function clean() {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $modeluser = User::find($user->id);
        $modeluser->products()->detach();
        return true;
    }

    public function getOrder() {
        
    }

}
