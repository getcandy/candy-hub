<?php

namespace GetCandy\Http\Requests\Api\Categories;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Categories\Models\Category;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return $this->user()->can('create', Category::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attributes.name' => 'required|unique_category_attribute:name',
            'attributes.url' => 'required|unique_category_attribute:slug',
        ];
    }

    public function messages()
    {
        return [
            'attributes.name.unique_category_attribute' => 'Name must be unique',
            'attributes.url.unique_category_attribute' => 'Url must be unique',
        ];
    }
}
