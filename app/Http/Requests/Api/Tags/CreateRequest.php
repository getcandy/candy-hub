<?php

namespace GetCandy\Http\Requests\Api\Tags;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Tags\Models\Tag;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }
    public function rules(Tag $tag)
    {
        return [
            'name' => 'array|required|valid_locales'
        ];
    }
}
