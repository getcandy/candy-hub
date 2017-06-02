<?php

namespace GetCandy\Listeners\Products;

use GetCandy\Events\Products\ProductCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GetCandy\Search\SearchContract;

class AddProductToIndexListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductCreatedEvent  $event
     * @return void
     */
    public function handle(ProductCreatedEvent $event)
    {
        $product = $event->product();
        app(SearchContract::class)->indexObject($product);
    }
}
