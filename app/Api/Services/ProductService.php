<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\Product;
use GetCandy\Exceptions\InvalidLanguageException;

class ProductService extends BaseService
{
    public function __construct()
    {
        $this->model = new Product();
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $id
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception
     * @throws GetCandy\Api\Exceptions\InvalidLanguageException
     *
     * @return GetCandy\Api\Models\Product
     */
    public function update($hashedId, array $data)
    {
        $product = $this->getByHashedId($hashedId);

        if (! $product) {
            abort(404);
        }

        foreach ($data['name'] as $lang => $value) {
            if (! app('api')->languages()->existsByCode($lang)) {
                throw new InvalidLanguageException(trans('getcandy_api::response.error.invalid_lang', ['lang' => $lang]), 422);
            }
        }

        if (! empty($data['name'])) {
            $product->name = json_encode($data['name']);
        }

        $product->save();

        return $product;
    }

    /**
     * Creates a resource from the given data
     *
     * @param  string $id
     *
     * @throws GetCandy\Api\Exceptions\InvalidLanguageException
     *
     * @return GetCandy\Api\Models\Product
     */
    public function create(array $data)
    {
        $product = $this->model;

        foreach ($data['name'] as $lang => $value) {
            if (! app('api')->languages()->existsByCode($lang)) {
                throw new InvalidLanguageException(trans('getcandy_api::response.error.invalid_lang', ['lang' => $lang]), 422);
            }
        }

        $product->name = json_encode($data['name']);
        $product->price = $data['price'];

        $product->save();

        return $product;
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
        $product = $this->getByHashedId($hashedId);
        if (!$product) {
            abort(404);
        }
        return $product->delete();
    }
}
