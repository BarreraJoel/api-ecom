<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\FilterService;
use App\Services\ProductService;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\ProductService $productService
     */
    public function __construct(
        private ProductService $productService
    ) {}

    /**
     * Summary of index
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'products' => ProductService::getAll(true)
        ]);
    }

    /**
     * 
     * @param \App\Http\Requests\Products\StoreProductRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        if (!ValidationService::validate($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data'
            ]);
        }

        $newProduct = ProductService::add($request);
        if (!$newProduct) {
            return response()->json([
                'success' => false,
                'message' => 'Product not stored'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product stored',
            'product' => new ProductResource($newProduct)
        ]);
    }

    /**
     * Summary of show
     * @param \App\Models\Product $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => $product ? ProductService::toResource($product) : null
        ]);
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\Products\UpdateProductRequest $request
     * @param \App\Models\Product $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if (!ValidationService::validate($request)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data'
            ]);
        }

        if (!ProductService::update($request, $product)) {
            return response()->json([
                'success' => false,
                'message' => 'Product not updated'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product updated',
            'product' => new ProductResource($product)
        ]);
    }

    /**
     * Summary of destroy
     * @param \App\Models\Product $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        if (!ProductService::delete($product)) {
            return response()->json([
                'success' => false,
                'message' => 'Product not deleted'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product deleted'
        ]);
    }

    public function filter(Request $request)
    {
        if (!($request->name || $request->price || $request->stock)) {
            return;
        }

        return FilterService::filterFull($request);
    }
}
