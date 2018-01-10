<?php

namespace GetCandy\Http\Requests\Api\Languages;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Languages\Models\Language;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Language::class);
        return $this->user()->hasRole('admin');
    }
    public function rules()
    {
        return [
            'name' => 'required',
            'lang' => 'required',
            'iso' => 'required|unique:languages,iso'
        ];
    }
}
