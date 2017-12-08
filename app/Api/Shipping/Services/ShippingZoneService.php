<?php
namespace GetCandy\Api\Shipping\Services;

use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Api\Shipping\Models\ShippingZone;

class ShippingZoneService extends BaseService
{
    public function __construct()
    {
        $this->model = new ShippingZone();
    }

    /**
     * Create a shipping method
     *
     * @param array $data
     *
     * @return ShippingZone
     */
    public function create(array $data)
    {
        $shipping = new ShippingZone;
        $shipping->fill($data);
        $shipping->save();
        return $shipping;
    }

    /**
     * Updates a shipping zone
     *
     * @param string $id
     * @param array $data
     *
     * @return ShippingZone
     */
    public function update($id, array $data)
    {
        $shipping = $this->getByHashedId($id);
        $shipping->fill($data);
        $shipping->save();
        return $shipping;
    }
}
