<?php
namespace GetCandy\Plugins\ReviewsRatings\Providers;

use GetCandy\Scaffold\PluginServiceProvider;

class ReviewsRatingsServiceProvider extends PluginServiceProvider
{
    protected $migrations = [
        __DIR__ . '/../../database/migrations'
    ];

    protected $routeFiles = [
        __DIR__ . '/../../routes/api.php',
        __DIR__ . '/../../routes/web.php'
    ];

    protected $viewDirs = [
        'reviewsratings' => __DIR__ . '/../../resources/views'
    ];

    protected $listen = [
        'GetCandy\Api\Events\ViewProductEvent' => [
            \GetCandy\Plugins\ReviewsRatings\Listeners\AddReviewsToProductListener::class
        ],
        'cms.head_html' => [
            \GetCandy\Plugins\ReviewsRatings\Listeners\AddReviewsCssListener::class
        ],
        'cms.scripts_html' => [
            \GetCandy\Plugins\ReviewsRatings\Listeners\AddReviewsScriptsListener::class
        ],
        'cms.navigation.pre_render' => [
            \GetCandy\Plugins\ReviewsRatings\Listeners\AddReviewsToMenusListener::class
        ]
    ];
}
