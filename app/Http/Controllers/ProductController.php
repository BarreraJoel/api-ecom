<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\FilterService;
use App\Services\ProductService;
use App\Services\RequestService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * 
     * @param \App\Services\ProductService $productService
     */
    public function __construct(private ProductService $productService) {}

    /**
     * Lista todos los productos
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return ApiResponse::response(
            true,
            null,
            [
                'products' => ProductService::getAll(true)
            ]
        );
    }

    /**
     * Agrega un producto
     * @param \App\Http\Requests\Products\StoreProductRequest $request
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $newProduct = ProductService::add($request);
        if (!$newProduct) {
            throw new Exception('Hubo un error al agregar el producto', Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        return ApiResponse::response(
            true,
            'Producto agregado',
            [
                'product' => new ProductResource($newProduct)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Busca un producto mediante un id
     * @param \App\Models\Product $product Producto buscado
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return ApiResponse::response(
            true,
            null,
            [
                'product' => $product ? ProductService::toResource($product) : null
            ]
        );
    }

    /**
     * Actualiza el registro de un producto
     * @param \App\Http\Requests\Products\UpdateProductRequest $request
     * @param \App\Models\Product $product Producto a modificar
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function update(UpdateProductRequest $request, Product $product)
    {
        if (!RequestService::validate($request, ['name', 'description', 'price', 'stock', 'image'])) {
            throw new BadRequestException('Debe agregar algún parámetro');
        }
        
        if (!ProductService::update($request, $product)) {
            throw new Exception('Hubo un error al actualizar el producto', Response::HTTP_NOT_MODIFIED);
        }

        return ApiResponse::response(
            true,
            'Producto actualizado',
            [
                'product' => new ProductResource($product)
            ]
        );
    }

    /**
     * Borra el registro de un producto
     * @param \App\Models\Product $product Producto a borrar
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Product $product)
    {
        if (!ProductService::delete($product)) {
            throw new Exception('No se pudo eliminar el producto', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return ApiResponse::response(
            true,
            null,
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Busca y filtra según los parametros dados
     * @param \Illuminate\Http\Request $request
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException Si no se recibió ningún parametro para filtrar
     * @return array Array de productos coincidentes
     */
    public function filter(Request $request)
    {
        if (!($request->name || $request->price || $request->stock)) {
            throw new BadRequestException('Se requiere de algún campo');
        }

        return ApiResponse::response(
            true,
            null,
            ProductService::filter($request)
        );
    }
}
