<?php

namespace GetCandy\Api\Traits;

use Carbon\Carbon;

trait HasChannels
{
    public function scopeChannel($query, $channel = null)
    {
        $channels = app('api')->channels();

        // If no channel is set, we need to get the default one.
        if (!$channel) {
            $channel = $channels->getDefaultRecord()->handle;
        }

        $result = $query->whereHas('channels', function ($query) use ($channel) {
            $query->whereHandle($channel)->whereDate('published_at', '<', Carbon::now());
        });

        // return 'foo';
    }
}
