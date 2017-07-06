<?php

namespace GetCandy\Api\Products\Services;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\InvalidLanguageException;
use GetCandy\Search\SearchContract;
use GetCandy\Events\Products\ProductCreatedEvent;

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

        $product->attribute_data = $data['attributes'];

        if (! empty($data['family_id'])) {
            // This keeps the data mapping up to date, we don't go through the sync
            // job since it's only one record.
            $family = app('api')->productFamilies()->getByHashedId($data['family_id']);
            $data = $product->attribute_data;
            foreach ($family->attributes()->get() as $attribute) {
                if (empty($product->attribute_data[$attribute->handle])) {
                    $data[$attribute->handle] = $product->getDataMapping();
                }
                $product->attribute_data = $data;
            }
            $family->products()->save($product);
        } else {
            $product->save();
        }

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

        $product->attribute_data = $data['attributes'];

        $layout = app('api')->layouts()->getByHashedId($data['layout_id']);
        $product->layout()->associate($layout);

        if (! empty($data['family_id'])) {
            $family = app('api')->productFamilies()->getByHashedId($data['family_id']);
            if (! $family) {
                abort(422);
            }
            $family->products()->save($product);
        } else {
            $product->save();
        }

        $this->createVariant($product, ['sku' => $data['sku']]);

        // event(new ProductCreatedEvent($product));
        return $product;
    }

    /**
     * Creates a product variant
     * @param  Product $product
     * @param  array   $data
     * @return ProductVariant
     */
    public function createVariant(Product $product, array $data = [])
    {
        $data['attribute_data'] = $product->attribute_data;
        return $product->variants()->create($data);
    }

    /**
     * Deletes a resource by its given hashed ID
     *
     * @param  string $id
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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

    /**
     * Gets paginated data for the record
     * @param  integer $length How many results per page
     * @param  int  $page   The page to start
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedData($searchTerm = null, $length = 50, $page = null)
    {
        if ($searchTerm) {
            $ids = app(SearchContract::class)->against(get_class($this->model))->with($searchTerm);
            $results = $this->model->whereIn('id', $ids);
        } else {
            $results = $this->model;
        }
        return $results->paginate($length, ['*'], 'page', $page);
    }

    /**
     * Gets the attributes from a given products id
     * @param  string $id
     * @return array
     */
    public function getAttributes($id)
    {
        $id = $this->getDecodedId($id);
        $attributes = [];

        if (!$id) {
            return [];
        }

        $product = $this->model
            ->with(['attributes', 'family', 'family.attributes'])
            ->find($id);

        foreach ($product->family->attributes as $attribute) {
            $attributes[$attribute->handle] = $attribute;
        }

        // Direct attributes override family ones
        foreach ($product->attributes as $attribute) {
            $attributes[$attribute->handle] = $attribute;
        }

        return $attributes;
    }
}
