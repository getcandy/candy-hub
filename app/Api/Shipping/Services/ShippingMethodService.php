<?php
namespace GetCandy\Api\Shipping\Services;

use GetCandy\Api\Attributes\Events\AttributableSavedEvent;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Shipping\Models\ShippingMethod;
use GetCandy\Api\Shipping\Models\ShippingZone;
use GetCandy\Api\Shipping\ShippingCalculator;

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
        event(new AttributableSavedEvent($shipping));
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

    public function getForOrder($orderId)
    {
        // // Get the zones for this order...
        $order = app('api')->orders()->getByHashedId($orderId);
        $zones = app('api')->shippingZones()->getByCountryName($order->shipping_details['country']);

        $calculator = new ShippingCalculator(app());

        $options = [];

        foreach ($zones as $zone) {
            foreach ($zone->methods as $index => $method) {
                $options[$index] = $calculator->with($method)->calculate($order);
            }
        }
        return collect($options);
    }
}
