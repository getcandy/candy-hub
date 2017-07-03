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
        $model = $event->model;
        $data = $model->attribute_data;
        $mapping = app('api')->attributes()->getDataMapping($data);

        foreach ($model->attributes as $attribute) {
            if (empty($data[$attribute->handle])) {
                $data[$attribute->handle] = $mapping;
            }
        }

        $model->attribute_data = $data;
        $model->save();
    }
}
