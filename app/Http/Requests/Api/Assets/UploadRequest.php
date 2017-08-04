<?php

namespace GetCandy\Http\Requests\Api\Assets;

use GetCandy\Http\Requests\Api\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', Attribute::class);
        return true;
    }
    public function rules()
    {
        return [
//            'file' => 'required|file'
        ];
    }
}
