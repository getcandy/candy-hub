<?php

namespace GetCandy\Http\Requests\Api\Channels;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Channels\Models\Channel;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Channel::class);
        return true;
    }
    public function rules()
    {
        return [
            'name' => 'required|unique:channels,name',
        ];
    }
}
