<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Channels\Models\Channel;

class ChannelTransformer extends BaseTransformer
{
    protected $availableIncludes = [];

    public function transform(Channel $channel)
    {
        return [
            'id' => $channel->encodedId(),
            'name' => $channel->name,
            'default' => (bool) $channel->default
        ];
    }
}
