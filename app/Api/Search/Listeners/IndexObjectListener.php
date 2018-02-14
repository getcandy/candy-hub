<?php

namespace GetCandy\Api\Search\Listeners;

use GetCandy\Api\Search\Events\IndexableSavedEvent;
use GetCandy\Search\SearchContract;

class IndexObjectListener
{
    /**
     * Handle the event.
     *
     * @param  ProductCreatedEvent  $event
     * @return void
     */
    public function handle(IndexableSavedEvent $event)
    {
        app(SearchContract::class)->indexer()->indexObject(
            $event->indexable()
        );
    }
}
