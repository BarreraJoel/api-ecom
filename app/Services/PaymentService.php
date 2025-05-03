<?php

namespace App\Services;

use App\Models\User;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentService
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function pay()
    {
        $user = $this->authService->getCurrentUser();
        $modelUser = User::find($user->id);
        $listItems = [];

        foreach ($modelUser->products as $product) {
            $lineItem = [
                'price_data' => [
                    'currency' => 'ars',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $product->pivot->quantity,
            ];

            array_push($listItems, $lineItem);
        }

        $session = Session::create([
            'line_items' => $listItems,
            'mode' => 'payment',
            'success_url' => url('/'),
            'cancel_url' => url('/cancel'),
        ]);

        return $session;
    }
}
