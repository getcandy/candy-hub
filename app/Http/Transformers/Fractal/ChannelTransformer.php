<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Channels\Models\Channel;

class ChannelTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'routes'
    ];

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
