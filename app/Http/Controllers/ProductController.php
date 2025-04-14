<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\FilterService;
use App\Services\ProductService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

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

    public function store(StoreProductRequest $request)
    {
        try {
            if (!ValidationService::validate($request)) {
                throw new BadRequestException('Data invalida', Response::HTTP_BAD_REQUEST);
            }

            $newProduct = ProductService::add($request);
            if (!$newProduct) {
                throw new Exception('Hubo un error al agregar el producto', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => 'Producto agregado',
                'product' => new ProductResource($newProduct)
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * 
     * @param \App\Models\Product $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        try {
            return response()->json([
                'product' => $product ? ProductService::toResource($product) : null
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * 
     * @param \App\Http\Requests\Products\UpdateProductRequest $request
     * @param \App\Models\Product $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            if (!ValidationService::validate($request)) {
                throw new BadRequestException('Data invalida', Response::HTTP_BAD_REQUEST);
            }

            if (!ProductService::update($request, $product)) {
                throw new Exception('Hubo un error al actualizar el producto', Response::HTTP_NOT_MODIFIED);
            }

            return response()->json([
                'message' => 'Producto actualizado',
                'product' => new ProductResource($product)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * 
     * @param \App\Models\Product $product
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        try {
            if (!ProductService::delete($product)) {
                throw new Exception('Producto eliminado', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Collection<int, Product>
     */
    public function filter(Request $request)
    {
        try {

            if (!($request->name || $request->price || $request->stock)) {
                throw new BadRequestException('Se requiere de algún campo', Response::HTTP_BAD_REQUEST);
            }
            return FilterService::filter($request);
        } catch (\Throwable $th) {

            return response()->json([
                'message' => $th->getMessage()
            ], $th->getCode());
        }
    }
}
