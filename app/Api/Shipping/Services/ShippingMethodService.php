<?php
namespace GetCandy\Api\Shipping\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Shipping\Models\ShippingMethod;
use GetCandy\Api\Shipping\Models\ShippingZone;

class ShippingMethodService extends BaseService
{
    public function __construct()
    {
        $this->model = new ShippingMethod();
    }

    /**
     * Create a shipping method
     *
     * @param array $data
     *
     * @return ShippingMethod
     */
    public function create(array $data)
    {
        $shipping = new ShippingMethod;
        $shipping->attribute_data = $data;
        $shipping->type = $data['type'];
        $shipping->save();
        return $shipping;
    }

    /**
     * Update a shipping method
     *
     * @param string $id
     * @param array $data
     *
     * @return ShippingMethod
     */
    public function update($id, array $data)
    {
        $shipping = $this->getByHashedId($id);
        $shipping->attribute_data = $data;
        $shipping->type = $data['type'];
        $shipping->save();
        return $shipping;
    }
}
