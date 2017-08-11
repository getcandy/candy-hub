<?php

namespace GetCandy\Http\Requests\Api\Tags;

use Auth;
use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Tags\Models\Tag;

class DeleteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [];
    }
}
