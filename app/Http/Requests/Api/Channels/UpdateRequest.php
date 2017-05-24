<?php

namespace GetCandy\Http\Requests\Api\Channels;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Channel;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', Channel::class);
        return true;
    }
    public function rules(Channel $channel)
    {
        return [
            'name' => 'unique:channels,name,'. $channel->decodeId($this->channel),
        ];
    }
}
