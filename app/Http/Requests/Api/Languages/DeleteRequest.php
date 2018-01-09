<?php

namespace GetCandy\Http\Requests\Api\Languages;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Languages\Models\Language;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('delete', Language::class);
        return $this->user()->hasRole('admin');
    }

    public function rules()
    {
        return [
        ];
    }
}
