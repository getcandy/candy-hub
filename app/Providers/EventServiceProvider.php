<?php

namespace GetCandy\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use GetCandy\Api\Attributes\Events\AttributableSavedEvent;
use GetCandy\Api\Attributes\Listeners\SyncAttributablesListener;
use GetCandy\Api\Products\Events\ProductCreatedEvent;
use GetCandy\Api\Products\Events\ProductUpdatedEvent;
use GetCandy\Api\Products\Events\ProductViewedEvent;
use GetCandy\Api\Products\Listeners\AddToIndexListener as ProductIndexListener;
use GetCandy\Api\Discounts\Listeners\AddDiscountToProductListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AttributableSavedEvent::class => [
            SyncAttributablesListener::class
        ],
        ProductCreatedEvent::class => [
            ProductIndexListener::class
        ],
        ProductUpdatedEvent::class => [
            ProductIndexListener::class
        ],
        ProductViewedEvent::class => [
            AddDiscountToProductListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
