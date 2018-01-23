<?php
namespace GetCandy\Plugins\Inventory\Listeners;

use GetCandy\Api\Orders\Events\OrderProcessedEvent;

class UpdateInventoryListener
{
    /**
     * Handle the event.
     *
     * @param  OrderShipped  $event
     * @return void
     */
    public function handle(OrderProcessedEvent $event)
    {
        if ($event->order->status == 'payment-received') {
            $basket = $event->order->basket;
            foreach ($basket->lines as $line) {
                $variant = $line->variant;
                $variant->stock -= $line->quantity;
                $variant->save();
            }
        }
    }
}
