<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Models\Channel;
use League\Fractal\TransformerAbstract;
use GetCandy\Api\Services\Hashids\ChannelHashidService;

class ChannelTransformer extends TransformerAbstract
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
