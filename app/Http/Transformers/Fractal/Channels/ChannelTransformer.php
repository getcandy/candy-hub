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
        return [
            'id' => $channel->encodedId(),
            'name' => $channel->name,
            'default' => (bool) $channel->default
        ];
    }

    public function includeRoutes(Channel $channel)
    {
        return $this->item($channel->routes, new RouteTransformer);
    }
}
