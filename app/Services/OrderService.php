<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

use function Termwind\parse;

class OrderService
{
    private AuthService $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }

    public function getAll(){
        return Order::all();
    }

    public function create()
    {
        $user = $this->authService->getCurrentUser(false);

        $order = Order::create([
            'user_id' => $user->id,
            'amount' => $this->calculateTotal()
        ]);

        return $order;
    }

    private function calculateTotal()
    {
        $user =  $this->authService->getCurrentUser(false);
        if (!isset($user)) {
            return null;
        }

        $total = 0;
        foreach ($user->products as $product) {
            $total += (int)$product->pivot->unit_price * $product->pivot->quantity;
        }
        
        return $total;
    }

    public function getOrder(int $id)
    {
        return Order::find($id);
    }
}
