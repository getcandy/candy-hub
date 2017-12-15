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

        // If we are adding a new set of variants, get rid.
        if ($product->variants->count() == 1) {
            $product->variants()->delete();
        }

        $options = $product->option_data;

        foreach ($data['variants'] as $newVariant) {
            foreach ($newVariant['options'] as $handle => $option) {
                foreach ($option as $lang => $value) {
                    $optionKey = str_slug($value);
                    // If this is the first time this option is being set...
                    if (empty($options[$handle])) {
                        $options[$handle]['label'][$lang] = title_case($handle);
                    }
                    $options[$handle]['options'][$optionKey]['values'][$lang] = $value;
                }
            }

            $sku = $newVariant['sku'];
            $i = 1;
            while (app('api')->productVariants()->existsBySku($sku)) {
                $sku = $sku . $i;
                $i++;
            }

            $variant = $product->variants()->create([
                'price' => $newVariant['price'],
                'sku' => $sku,
                'stock' => $newVariant['inventory'],
                'options' => $newVariant['options'],
                'pricing' => $this->getPriceMapping($newVariant['price'])
            ]);

            $this->setMeasurements($variant, $newVariant);

            $variant->save();
        }

        $product->update(['option_data' => $options]);

        return $product;
    }

    protected function getPriceMapping($price)
    {
        $customerGroups = app('api')->customerGroups()->all();
        return $customerGroups->map(function ($group) use ($price) {
            return [
                $group->handle => [
                    'price' => $price,
                    'compare_at' => 0,
                    'tax' => 0
                ]
            ];
        })->toArray();
    }

    public function existsBySku($sku)
    {
        return $this->model->where('sku', '=', $sku)->exists();
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

        $options = $variant->product->option_data;

        foreach ($data['options'] as $handle => $option) {
            foreach ($option as $lang => $value) {
                $optionKey = str_slug($value);
                // If this is the first time this option is being set...
                if (empty($options[$handle])) {
                    $options[$handle]['label'][$lang] = title_case($value);
                }
                $options[$handle]['options'][$optionKey]['values'][$lang] = $value;
                $newOption[$handle] = $optionKey;
            }
        }

        $variant->product->update(['option_data' => $options]);
        $variant->fill($data);


        $thumbnailId = null;

        if (!empty($data['thumbnail'])) {
            $thumbnailId = $data['thumbnail']['data']['id'];
        } elseif (!empty($data['thumbnail_id'])) {
            $thumbnailId = $data['thumbnail_id'];
        }

        if ($thumbnailId) {
            $asset = app('api')->assets()->getByHashedId($thumbnailId);
            $variant->image()->associate($asset);
        }

        $this->setMeasurements($variant, $data);

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

    /**
     * Maps and sets the measurements for a variant
     * @param ProductVariant $variant
     * [
     *     'weight' => [
     *         'cm' => 100
     *     ]
     * ]
     * @param array $data
     */
    protected function setMeasurements($variant, $data)
    {
        $measurements = ['weight', 'height', 'width', 'depth', 'volume'];

        array_map(function ($x) use ($data, $variant) {
            if (!empty($data[$x])) {
                foreach ($data[$x] as $label => $value) {
                    $variant->setAttribute($x . '_' . $label, is_numeric($value) ? $value : $value);
                }
            }
        }, $measurements);
    }
}
