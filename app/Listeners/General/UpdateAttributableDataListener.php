<?php

namespace GetCandy\Listeners\General;

use GetCandy\Events\General\AttributesUpdatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateAttributableDataListener
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
     * @param  AttributesUpdatedEvent  $event
     * @return void
     */
    public function handle(AttributesUpdatedEvent $event)
    {
        // We do this to trigger the mutator and give us an array
        // with any extra attributes
        $model = $event->model;
        $model->attribute_data = $model->attribute_data;
        $model->save();
    }
}
