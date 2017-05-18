<?php

namespace GetCandy\Api\Products\Services;

use GetCandy\Api\Languages\LanguageManager;
use GetCandy\Api\Products\Repositories\ProductRepository;
use GetCandy\Exceptions\InvalidLanguageException;

class ProductService
{
    /**
     * @var LanguageService
     */
    protected $languageManager;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    public function __construct(
        LanguageManager $languageManager,
        ProductRepository $productRepo
    ) {
        $this->languageManager = $languageManager;
        $this->productRepo = $productRepo;
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
    public function update($id, $data)
    {
        $product = $this->productRepo->getByHashedId($id);

        if (! $product) {
            abort(404);
        }

        foreach ($data['name'] as $lang => $value) {
            if (! $this->languageManager->existsByCode($lang)) {
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
        $product = $this->productRepo->getNew();

        foreach ($data['name'] as $lang => $value) {
            if (! $this->languageManager->existsByCode($lang)) {
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
    public function deleteByHashedId($hashedId)
    {
        $product = $this->productRepo->getByHashedId($hashedId);
        if (!$product) {
            abort(404);
        }
        return $product->delete();
    }
}
