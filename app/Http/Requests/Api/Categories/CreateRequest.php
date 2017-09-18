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
            'name' => 'required|unique_category_attribute:name',
            'slug' => 'required|unique_category_attribute:slug',
        ];
    }

    public function messages()
    {
        return [
            'name.unique_category_attribute' => 'Name must be unique',
            'slug.unique_category_attribute' => 'Slug must be unique',
        ];
    }
}
