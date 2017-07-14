<?php

namespace GetCandy\Http\Transformers\Fractal\Channels;

use GetCandy\Api\Channels\Models\Channel;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class ChannelTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'routes'
    ];

    /**
     * Decorates the attribute object for viewing
     * @param  Attribute $product
     * @return Array
     */
    public function transform(Channel $channel)
    {
        

        if ($channel->pivot) {
            $data[$channel->encodedId()] = [
                'name' => $channel->name,
                'visible' => (bool) $channel->pivot->visible,
                'published_at' =>  \Carbon\Carbon::parse($channel->pivot->published_at)
            ];
        } else {
            $data = [
                'id' => $channel->encodedId(),
                'name' => $channel->name,
                'default' => (bool) $channel->default
            ];
        }

        return $data;
    }

    public function includeRoutes(Channel $channel)
    {
        return $this->item($channel->routes, new RouteTransformer);
    }
}
