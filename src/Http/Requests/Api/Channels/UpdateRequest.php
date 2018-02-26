<?php

namespace GetCandy\Http\Requests\Api\Channels;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Channels\Models\Channel;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }
    public function rules(Channel $channel)
    {
        return [
            'name' => 'unique:channels,name,'. $channel->decodeId($this->channel),
            'handle' => 'unique:channels,handle,'. $channel->decodeId($this->channel),
        ];
    }
}
