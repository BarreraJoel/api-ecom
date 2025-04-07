<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\MercadoPagoService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function __construct(private PaymentService $paymentService, private MercadoPagoService $mercadoPagoService, private CartService $cartService) {}

    public function checkout(Request $request)
    {
        $session = $this->paymentService->pay();
        return response()->json(['url' => $session->url]);
    }

    public function checkoutMp(Request $request)
    {
        $products = $this->cartService->getAll();
        $preference = $this->mercadoPagoService->pay($products);

        return response()->json(['preference' => $preference]);
    }

    public function receivePay(Request $request) {
        Log::info($request);
    }
}
