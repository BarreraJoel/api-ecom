<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Events\OrderCreatedEvent;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CheckoutController extends Controller
{

    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private MercadoPagoService $mercadoPagoService,
        private PaymentService $paymentService,
        private CheckoutService $checkoutService,
    ) {}

    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function checkoutStripe(Request $request)
    {
        $session = $this->paymentService->pay();

        return ApiResponse::response(
            true,
            null,
            [
                'url' => $session->url
            ]
        );
    }

    /**
     * Genera la preferencia de pago de Mercado Pago con los datos de la compra
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function checkoutMp(Request $request)
    {

        $preference = Cache::lock('generarCheckout')->block(10, function () {
            if (!$this->checkoutService->verifyStockAvailable()) {
                throw new UnprocessableEntityHttpException('La cantidad de items ya no se encuentra disponible');
            }

            return DB::transaction(function () {
                $cartProducts = $this->cartService->getAll();
                $order = $this->orderService->create();
                $preference = $this->mercadoPagoService->createPreference($cartProducts, $order->id);
                $this->cartService->clean();
                OrderCreatedEvent::dispatch($cartProducts);
                return $preference;
            });
        });

        return ApiResponse::response(
            true,
            null,
            [
                'url' => $preference->init_point
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Recibe un pago y actualiza su estado
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function receivePay(Request $request)
    {
        $payment = $this->mercadoPagoService->getPayment($request->id);
        $idOrder = $payment->external_reference;
        $order = $this->orderService->getOrder($idOrder);
        $order->status = $payment->status;
        $order->save();

        return ApiResponse::response(
            true,
            null,
            [
                'message' => 'Pago actualizado'
            ]
        );
    }
}
