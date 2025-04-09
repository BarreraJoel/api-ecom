<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductService
{
    public function __construct() {}

    public static function getAll(bool $withResource = false)
    {
        return $withResource ? ProductService::getAllWithResource() : Product::all();
    }

    private static function getAllWithResource()
    {
        $products = Product::all();
        $productsWithResource = [];

        foreach ($products as $product) {
            array_push($productsWithResource, ProductService::toResource($product));
        }

        return $productsWithResource;
    }

    public static function toResource(Product $product)
    {
        return new ProductResource($product);
    }
    public static function get($id, bool $withResource = false)
    {
        $product = Product::find($id);
        if (!isset($product)) {
            return null;
        }

        return $withResource ? ProductService::toResource($product) : $product;
    }

    public static function add($request)
    {
        $product = Product::create($request->all());

        if (!isset($product)) {
            return null;
        }

        return $product;
    }

    public static function delete(Product $product) {
        return $product->delete();
    }

    public static function update($request, Product $product)
    {
        $updated = false;

        try {
            $product->update($request->all());
            $updated = true;
        } catch (\Throwable $th) {
            $updated = false;   
        }
        finally{
            return $updated;

        }

    }
}
