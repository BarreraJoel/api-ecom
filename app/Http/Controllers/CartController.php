<?php

namespace App\Http\Controllers;

use App\Models\ItemCart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function listItems()
    {
        return response()->json([
            'cart' => $this->cartService->getAll()
        ]);
    }

    public function addItem(Request $request)
    {
        if (!$this->cartService->add(new ItemCart($request->all()))) {
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added'
        ]);
    }

    public function removeItem(Request $request)
    {
        if (!$this->cartService->remove($request->item_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed'
        ]);
    }

    public function empty(Request $request)
    {
        if (!$this->cartService->clean()) {
            return response()->json([
                'success' => false,
                'message' => 'Error'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Empty cart'
        ]);
    }
}
