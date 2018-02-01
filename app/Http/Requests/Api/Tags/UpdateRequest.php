<?php

namespace GetCandy\Http\Requests\Api\Tags;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }
    public function rules()
    {
        $decodedId = app('api')->tags()->getDecodedId($this->tag);
        return [
            'name' => 'required|array|valid_locales'
        ];
    }
}
