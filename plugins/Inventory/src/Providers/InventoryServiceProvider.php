<?php
namespace GetCandy\Plugins\Inventory\Providers;

use GetCandy\Scaffold\PluginServiceProvider;

use GetCandy\Api\Orders\Events\OrderBeforeSavedEvent;
use GetCandy\Plugins\TrackingNotify\Listeners\SendTrackingNotificationListener;
use GetCandy\Plugins\TrackingNotify\Services\TrackingNotifyService;
use GetCandy\Api\Orders\Events\OrderProcessedEvent;
use GetCandy\Plugins\Inventory\Listeners\UpdateInventoryListener;

class InventoryServiceProvider extends PluginServiceProvider
{
    protected $listen = [
        OrderProcessedEvent::class => [
            UpdateInventoryListener::class
        ]
    ];
}
