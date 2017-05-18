<?php

namespace GetCandy\Http\ViewComposers;

use Event;

abstract class BaseComposer
{
    protected function assemble($event)
    {
        $items = [];

        list($items) = Event::fire($event, [$items]);

        return implode(PHP_EOL, $items);
    }
}
