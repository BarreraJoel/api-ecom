<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class OrderService
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function create()
    {
        $user = $this->authService->getCurrentUser();

        $order = Order::create([
            'user_id' => $user->id,
            'amount' => $this->calculateTotal()
        ]);

        return $order;
    }

    private function calculateTotal()
    {
        $user =  AuthService::getCurrentUser();
        if (!isset($user)) {
            return null;
        }

        $modeluser = User::find($user->id);
        $total = 0;
        // dd($modeluser->products);
        foreach ($modeluser->products as $product) {
            $total += $product->pivot->price_unit * $product->pivot->quantity;
        }

        return $total;
    }

    public function getOrder(int $id)
    {
        return Order::find($id);
    }
}
