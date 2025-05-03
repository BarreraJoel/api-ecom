<?php

namespace App\Services;

use App\Models\ItemCart;
use App\Models\ItemPivot;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class CartService
{
    private AuthService $authService;
    public function __construct(private ProductService $productService)
    {
        $this->authService = new AuthService();
    }

    public function getAll()
    {
        $user =  $this->authService->getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        return $this->generateArrayItems($user->products);
    }

    public function add(Request $request)
    {
        $user =  $this->authService->getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $itemCart = $this->createItem($request);
        $user->products()->attach(
            $itemCart->product_id,
            [
                'quantity' => $itemCart->quantity,
                'unit_price' => $itemCart->unit_price,
            ]
        );
        return true;
    }

    public function itemExists(int $productId)
    {
        $items = $this->getAll();

        foreach ($items as $auxItem) {
            if ($auxItem->product_id == $productId) {
                return true;
            }
        }

        return false;
    }

    public function update(int $quantity, int $productId)
    {
        $user = $this->authService->getCurrentUser();

        foreach ($user->products as $product) {
            if ($product->id == $productId) {
                if(!$this->verifyStock($product->pivot->quantity, $quantity, $product->stock)) {
                    throw new BadRequestException('La cantidad a actualizar supera el limite del stock');
                }
                
                $lastPivot = $product->pivot;
                $user->products()->detach($productId);
                $user->products()->attach(
                    $productId,
                    [
                        'quantity' => $quantity + $lastPivot->quantity,
                        'unit_price' => $lastPivot->unit_price,
                    ]
                );
            }
        }

        return true;
    }

    private function verifyStock(int $pivotQuantity, int $quantity, $stock) {
        return $pivotQuantity + $quantity <= $stock;
    }

    public function remove($productId)
    {
        $user = $this->authService->getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $user->products()->detach($productId);

        return true;
    }

    public function clean()
    {
        $user = $this->authService->getCurrentUser(false);
        if (!isset($user)) {
            return null;
        }

        $user->products()->detach();
        return true;
    }

    private function createItem(Request $request)
    {
        $product = $this->productService->get($request->product_id);
        return new ItemPivot([
            'product_id' =>  (int)$request->product_id,
            'quantity' =>  (int)$request->quantity,
            'unit_price' => $product->price
        ]);
    }

    private function generateArrayItems($products)
    {
        $items = [];

        foreach ($products as $product) {
            array_push($items, new ItemCart([
                'user_id' => $product->pivot->user_id,
                'product_id' => $product->pivot->product_id,
                'quantity' => $product->pivot->quantity,
                'unit_price' => $product->pivot->unit_price,
            ]));
        }

        return $items;
    }

    public function validateRequest($request, int $itemId)
    {
        $product = ProductService::get($itemId);
        if(!isset($product)) {
            throw new ModelNotFoundException('Producto no encontrado');
        }

        if (!RequestService::lessThanOrEqual($request, 'quantity', $product->stock)) {
            throw new BadRequestException('La cantidad ingresada supera el stock del producto');
        }
    }
}
