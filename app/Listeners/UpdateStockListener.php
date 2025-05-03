<?php

namespace App\Listeners;

use App\Events\OrderCreatedEvent;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStockListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreatedEvent $event): void
    {
        foreach ($event->cartProducts as $cartProduct) {
            $product = Product::find($cartProduct->product_id);
            $product->stock -= $cartProduct->quantity;   
            $product->save();
        }
    }
}
