<?php

namespace GetCandy\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use GetCandy\Api\Attributes\Events\AttributableSavedEvent;
use GetCandy\Api\Attributes\Listeners\SyncAttributablesListener;
use GetCandy\Events\ProductCreatedEvent;
use GetCandy\Listeners\Products\AddProductToIndexListener;

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
            AddProductToIndexListener::class
        ],
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
