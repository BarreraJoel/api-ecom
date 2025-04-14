<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    /**
     * 
     * @param \App\Services\CartService $cartService
     */
    public function __construct(private CartService $cartService) {}

    /**
     * 
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function listItems()
    {
        return response()->json([
            'cart' => $this->cartService->getAll()
        ]);
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function addItem(Request $request)
    {
        try {

            if (!$this->cartService->add($request)) {
                throw new Exception('Hubo un error al agregar el ítem', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => 'Item agregado'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function removeItem(Request $request)
    {
        try {
            
            if (!$this->cartService->remove($request->item_id)) {
                throw new Exception('Hubo un error al eliminar el ítem', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => 'Item eliminado'
            ]);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        }
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function empty(Request $request)
    {
        try {

            if (!$this->cartService->clean()) {
                throw new Exception('Hubo un error al eliminar los ítems', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json([
                'message' => 'Carrito vacío'
            ]);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => $th->getMessage(),
            ], $th->getCode());
        
        }
    }
}
