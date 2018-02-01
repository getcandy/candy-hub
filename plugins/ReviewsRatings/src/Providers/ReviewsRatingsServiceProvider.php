<?php
namespace GetCandy\Plugins\ReviewsRatings\Providers;

use GetCandy\Scaffold\PluginServiceProvider;

class ReviewsRatingsServiceProvider extends PluginServiceProvider
{
    protected $listen = [
        \GetCandy\Api\Orders\Events\OrderProcessedEvent::class => [
            \GetCandy\Plugins\ReviewsRatings\Listeners\PushReviewToQueueListener::class
        ]
    ];
}
