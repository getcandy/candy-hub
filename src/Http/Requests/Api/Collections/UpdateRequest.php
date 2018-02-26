<?php

namespace GetCandy\Http\Requests\Api\Collections;

use GetCandy\Http\Requests\Api\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->hasRole('admin');
    }
    public function rules()
    {
        return [
            'attributes' => 'required|array'
        ];
    }
}
