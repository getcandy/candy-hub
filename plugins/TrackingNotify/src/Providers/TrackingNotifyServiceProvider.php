<?php
namespace GetCandy\Plugins\TrackingNotify\Providers;

use GetCandy\Scaffold\PluginServiceProvider;

use GetCandy\Api\Orders\Events\OrderBeforeSavedEvent;
use GetCandy\Plugins\TrackingNotify\Listeners\SendTrackingNotificationListener;
use GetCandy\Plugins\TrackingNotify\Services\TrackingNotifyService;

class TrackingNotifyServiceProvider extends PluginServiceProvider
{
    protected $viewDirs = [
        'trackingnotify' => __DIR__ . '/../../resources/views'
    ];

    protected $routeFiles = [
        __DIR__ . '/../../routes/api.php'
    ];

    protected $listen = [
        OrderBeforeSavedEvent::class => [
            SendTrackingNotificationListener::class
        ]
    ];

    public function register()
    {
        $this->app->singleton('trackingnotify', function ($app) {
            return $app->make(TrackingNotifyService::class);
        });
    }
}
