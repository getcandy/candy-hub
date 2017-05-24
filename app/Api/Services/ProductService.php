<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Contracts\ServiceContract;
use GetCandy\Api\Repositories\Eloquent\ProductRepository;
use GetCandy\Exceptions\InvalidLanguageException;

class ProductService extends BaseService implements ServiceContract
{
    /**
     * @var ProductRepository
     */
    protected $repo;

    public function __construct(
        ProductRepository $repo
    ) {
        $this->repo = $repo;
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
        $product = $this->repo->getByHashedId($hashedId);

        if (! $product) {
            abort(404);
        }

        foreach ($data['name'] as $lang => $value) {
            if (! app('api')->languages()->dataExistsByCode($lang)) {
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
        $product = $this->repo->getNew();

        foreach ($data['name'] as $lang => $value) {
            if (! app('api')->languages()->dataExistsByCode($lang)) {
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
        $product = $this->repo->getByHashedId($hashedId);
        if (!$product) {
            abort(404);
        }
        return $product->delete();
    }
}
