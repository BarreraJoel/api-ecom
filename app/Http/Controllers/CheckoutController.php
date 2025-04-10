<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\MercadoPagoService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(private PaymentService $paymentService, private MercadoPagoService $mercadoPagoService, private CartService $cartService, private OrderService $orderService) {}

    public function checkout(Request $request)
    {
        $session = $this->paymentService->pay();
        return response()->json(['url' => $session->url]);
    }

    public function checkoutMp(Request $request)
    {
        $products = $this->cartService->getAll();
        $order = $this->orderService->create();
        $preference = $this->mercadoPagoService->pay($products, $order->id);
        $this->cartService->clean();
        return response()->json(['preference' => $preference]);
    }

    public function receivePay(Request $request)
    {
        $payment = $this->mercadoPagoService->getPayment($request->id);
        $idOrder = $payment->external_reference;
        $order = $this->orderService->getOrder($idOrder);
        $order->status = $payment->status;
        $order->save();

        return response()->json(
            [
                'message' => 'Payment received'
            ]
        );
    }
}
