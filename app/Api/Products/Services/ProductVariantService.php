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
    public function create($id, array $data)
    {
        $product = app('api')->products()->getByHashedId($id);

        if ($product->variants->count() == 1) {
            $product->variants()->delete();
        }

        $options = $product->option_data;

        foreach ($data['variants'] as $newVariant) {
            $newOption = [];
            foreach ($newVariant['options'] as $handle => $option) {
                foreach ($option as $lang => $value) {
                    $optionKey = str_slug($value);
                    $options[$handle]['options'][$optionKey]['values'][$lang] = $value;
                    $newOption[$handle] = $optionKey;
                }
            }
            $product->variants()->create([
                'price' => $newVariant['price'],
                'sku' => $newVariant['sku'],
                'stock' => $newVariant['inventory'],
                'options' => $newVariant['options']
            ]);
        }

        $product->update(['option_data' => $options]);

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
