<?php

namespace GetCandy\Api\Products\Services;

use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\InvalidLanguageException;

class ProductVariantService extends BaseService
{
    public function __construct()
    {
        $this->model = new ProductVariant();
    }

    /**
     * Creates variants for a product
     * @param  String $id
     * @param  array  $variant
     * @return boolean
     */
    public function create($id, array $variants)
    {
        $product = app('api')->products()->getByHashedId($id);

        foreach ($variants as $newVariant) {
            $product->variants()->create([
                'price' => $newVariant['price'],
                'sku' => $newVariant['sku'],
                'stock' => $newVariant['inventory'],
                'options' => $newVariant['data']
            ]);
        }
        return $product;
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $hashedId
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception
     *
     * @return GetCandy\Api\Models\ProductVariant
     */
    public function update($hashedId, array $data)
    {
        $variant = $this->getByHashedId($hashedId);
        $variant->fill($data);

        $measurements = ['weight', 'height', 'width', 'depth', 'volume'];

        array_map(function ($x) use ($data, $variant) {
            if (!empty($data[$x])) {
                foreach ($data[$x] as $label => $value) {
                    $variant->setAttribute($x . '_' . $label, is_numeric($value) ? $value : $value);
                }
            }
        }, $measurements);

        $variant->save();
        return $variant;
    }

    /**
     * Deletes a resource by its given hashed ID
     *
     * @param  string $id
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Boolean
     */
    public function delete($hashedId)
    {
        $productFamily = $this->getByHashedId($hashedId);
        if (!$productFamily) {
            abort(404);
        }
        return $productFamily->delete();
    }
}
