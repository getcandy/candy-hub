<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\ProductFamily;
use GetCandy\Exceptions\InvalidLanguageException;

class ProductService extends BaseService
{
    public function __construct()
    {
        $this->model = new ProductFamily();
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
     * @return GetCandy\Api\Models\ProductFamily
     */
    public function update($hashedId, array $data)
    {

    }

    /**
     * Creates a resource from the given data
     *
     * @param  string $id
     *
     * @return GetCandy\Api\Models\ProductFamily
     */
    public function create(array $data)
    {

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
