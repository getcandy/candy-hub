<?php

namespace GetCandy\Http\Requests\Api\Languages;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\Language;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('update', Language::class);
        return true;
    }
    public function rules(Language $language)
    {
        return [
            'name' => 'required',
            'code' => 'required|unique:languages,code,'. $language->decodeId($this->id)
        ];
    }
}
