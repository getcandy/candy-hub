<?php

namespace GetCandy\Http\Requests\Api\ProductFamilies;

use GetCandy\Http\Requests\Api\FormRequest;
use GetCandy\Api\Models\ProductFamily;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        // return $this->user()->can('create', ProductFamily::class);
        return true;
    }
    public function rules(ProductFamily $family)
    {
        return [
            'name' => 'required|unique:product_families,name,' . $family->decodeId($this->id),
        ];
    }
}
