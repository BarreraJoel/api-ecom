<?php

namespace App\Services;

use App\Models\ItemCart;
use App\Models\User;
use Illuminate\Http\Request;

class CartService
{
    public function __construct(private ProductService $productService) {}

    public function getAll()
    {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }
        return $user->products;
    }

    public function add(Request $request)
    {
        
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }
        
        $itemCart = $this->createItem($request);
        $modeluser = User::find($user->id);
        $modeluser->products()->attach(
            $itemCart->item_id,
            [
                'quantity' => $itemCart->quantity,
                'price_unit' => $itemCart->price_unit,
            ]
        );
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

    public function clean()
    {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $modeluser = User::find($user->id);
        $modeluser->products()->detach();
        return true;
    }

    private function createItem(Request $request)
    {
        $product = $this->productService->get($request->item_id);
        return new ItemCart([
            'item_id' =>  (int)$request->item_id,
            'quantity' =>  (int)$request->quantity,
            'price_unit' => $product->price
        ]);
    }

}
