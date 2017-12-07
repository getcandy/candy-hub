<?php
namespace GetCandy\Http\Requests\Api\Orders;

use GetCandy\Http\Requests\Api\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'address' => 'required',
            'city' => 'required',
            'county' => 'required_without:state',
            'email' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'phone' => 'required',
            'state' => 'required_without:county',
            'zip' => 'required',
            'country' => 'required'
        ];
    }
}
