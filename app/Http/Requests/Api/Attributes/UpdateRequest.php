<?php

namespace GetCandy\Http\Requests\Api\Attributes;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', AttributeGroup::class);
        return true;
    }
    public function rules()
    {
        $decodedId = app('api')->attributes()->getDecodedId($this->attribute);
        return [
            'name' => 'required|array|valid_locales',
            'filterable' => 'boolean',
            'searchable' => 'boolean',
            'position' => 'integer',
            'variant' => 'boolean',
            'handle' => 'unique:attributes,handle,'.$decodedId
        ];
    }
}
