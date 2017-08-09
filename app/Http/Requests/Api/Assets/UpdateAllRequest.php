<?php

namespace GetCandy\Http\Requests\Api\Assets;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class UpdateAllRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('delete', Attribute::class);
        return true;
    }
    public function rules()
    {
        return [
            'assets' => 'required|array'
        ];
    }
}
