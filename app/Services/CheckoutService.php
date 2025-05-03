<?php

namespace App\Services;

use App\Models\Product;

class CheckoutService
{
    public function __construct(
        private CartService $cartService,
        private ProductService $productService
    ) {}

    public function verifyStockAvailable()
    {
        $productsUser = Product::has('users')->get();

        $items = $this->cartService->getAll();

        foreach ($items as $item) {
            foreach ($productsUser as $productUser) {
                if ($item->product_id == $productUser->id) {
                    if ($productUser->stock < $item->quantity) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
