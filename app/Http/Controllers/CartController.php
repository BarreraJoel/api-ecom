<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Http\Requests\ItemPivotRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Services\CartService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    /**
     * 
     * @param \App\Services\CartService $cartService
     */
    public function __construct(private CartService $cartService) {}

    /**
     * Lista todos los items del carrito
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return ApiResponse::response(
            true,
            null,
            [
                'cart' => $this->cartService->getAll()
            ]
        );
    }

    /**
     * Agrega un item al carrito
     * @param \App\Http\Requests\ItemPivotRequest $request
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException Si el item ya existe en el carrito
     * @throws \Exception Si no se pudo agregar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(ItemPivotRequest $request)
    {
        $this->cartService->validateRequest($request, $request->product_id);

        if ($this->cartService->itemExists($request->product_id)) {
            throw new BadRequestException('El item ya existe en el carrito');
        }

        if (!$this->cartService->add($request)) {
            throw new Exception('Hubo un error al agregar el ítem');
        }

        return ApiResponse::response(
            true,
            'Item agregado',
            null,
            Response::HTTP_CREATED
        );
    }

    /**
     * 
     * @param \App\Http\Requests\UpdateItemRequest $request
     * @param int $id
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemRequest $request, int $id)
    {
        $this->cartService->validateRequest($request, $id);

        if (!$this->cartService->itemExists($id)) {
            throw new ModelNotFoundException('Producto no encontrado en el carrito');
        }
        
        if (!$this->cartService->update($request->quantity, $id)) {
            throw new Exception('Hubo un error al agregar el ítem');
        }

        return ApiResponse::response(
            true,
            'Item actualizado'
        );
    }

    /**
     * Elimina un item del carrito
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @throws \Symfony\Component\HttpFoundation\Exception\BadRequestException
     * @throws \Exception Si no se pudo eliminar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        if (!$this->cartService->itemExists($id)) {
            throw new BadRequestException('El item no existe');
        }

        if (!$this->cartService->remove($id)) {
            throw new Exception('Hubo un error al eliminar el ítem');
        }

        return ApiResponse::response(
            true,
            null,
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * Elimina todos los items del carrito
     * @param \Illuminate\Http\Request $request
     * @throws \Exception Si no se pudo vaciar
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy_all(Request $request)
    {
        if (!$this->cartService->clean()) {
            throw new Exception('Hubo un error al eliminar los ítems');
        }

        return ApiResponse::response(
            true,
            null,
            null,
            Response::HTTP_NO_CONTENT
        );
    }
}
