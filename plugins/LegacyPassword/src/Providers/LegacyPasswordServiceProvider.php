<?php
namespace GetCandy\Plugins\LegacyPassword\Providers;

use GetCandy\Scaffold\PluginServiceProvider;
use GetCandy\Plugins\LegacyPassword\Hashing\LegacyHasher;

class LegacyPasswordServiceProvider extends PluginServiceProvider
{
    protected $migrations = [
        __DIR__ . '/../../database/migrations'
    ];

    protected $commands = [
        \GetCandy\Plugins\LegacyPassword\Console\Commands\ImportLegacyPasswordsCommand::class
    ];

    protected $listen = [
        \GetCandy\Api\Orders\Events\OrderProcessedEvent::class => [
            \GetCandy\Plugins\ReviewsRatings\Listeners\PushReviewToQueueListener::class
        ]
    ];

    public function register()
    {
        $this->app->singleton('hash', function () {
            return new LegacyHasher;
        });
    }


    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['hash'];
    }
}
