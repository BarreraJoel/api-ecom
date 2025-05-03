<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct(private OrderService $orderService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = $this->orderService->getAll();
        return ApiResponse::response(
            true,
            null,
            [
                'orders' => $orders
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
