<?php

namespace GetCandy\Http\Requests\Api\Attributes;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Attributes\Models\Attribute;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('delete', Attribute::class);
        return true;
    }
    public function rules()
    {
        return [
        ];
    }
}
