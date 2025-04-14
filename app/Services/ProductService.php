<?php

namespace App\Services;

use App\Http\Requests\Products\UpdateProductRequest;
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
        $product = Product::create($request->has('image') ? $request->except('image') : $request->all());
        if ($request->has('image')) {
            ProductService::uploadImage($request->file('image'), $product);
        }

        if (!isset($product)) {
            return null;
        }

        return $product;
    }

    private static function uploadImage($file, $product)
    {
        $fileService = new FileService();
        $filename = $fileService->generateFileName($product->id);
        $path = $fileService->upload($file, '/products/images', $filename);
        $product->image_url = $path;
        return $product->save();
    }

    public static function delete(Product $product)
    {
        if ($product->image_url) {
            $fileService = new FileService();
            $fileService->removeImage($product->image_url);
        }
        
        return $product->delete();
    }

    public static function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $except = ['_method'];

            if ($request->hasFile('image')) {
                array_push($except, 'image');
                $fileService = new FileService();
                if ($product->image_url) {
                    $fileService->removeImage($product->image_url);
                }
                $filename = $fileService->generateFileName($product->id);
                $path = $fileService->upload($request->file('image'), 'products/images', $filename);
                $product->image_url = $path;
            }

            $product->fill(($request->except($except)));
            $product->update();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
