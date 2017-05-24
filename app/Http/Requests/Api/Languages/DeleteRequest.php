<?php

namespace GetCandy\Api\Http\Requests\Languages;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Language;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('delete', Language::class);
        return true;
    }

    public function rules()
    {
        return [
        ];
    }
}
