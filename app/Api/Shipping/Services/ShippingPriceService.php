<?php
namespace GetCandy\Api\Shipping\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Shipping\Models\ShippingPrice;

class ShippingPriceService extends BaseService
{
    public function __construct()
    {
        $this->model = new ShippingPrice();
    }

    /**
     * Create a shipping price
     *
     * @param array $data
     *
     * @return ShippingPrice
     */
    public function create($shippingMethodId, array $data)
    {
        $method = app('api')->shippingMethods()->getByHashedId($shippingMethodId);
        $price = new ShippingPrice;
        $price->fill($data);
        $price->method()->associate($method);
        $price->save();
        return $price;
    }

    /**
     * Updates a shipping price
     *
     * @param string $id
     * @param array $data
     *
     * @return ShippingPrice
     */
    public function update($id, array $data)
    {
        $shipping = $this->getByHashedId($id);
        $shipping->fill($data);
        $shipping->save();
        return $shipping;
    }

    /**
     * Delete a price
     *
     * @param string $id
     *
     * @return boolean
     */
    public function delete($id)
    {
        $price = $this->getByHashedId($id);
        return $price->delete();
    }
}
