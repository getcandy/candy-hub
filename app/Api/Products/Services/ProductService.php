<?php

namespace GetCandy\Api\Products\Services;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\InvalidLanguageException;
use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Search\SearchContract;
use GetCandy\Events\Products\ProductCreatedEvent;
use Illuminate\Database\Eloquent\Model;

class ProductService extends BaseService
{
    public function __construct()
    {
        $this->model = new Product();
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $hashedId
     * @param  array  $data
     *
     * @throws \Symfony\Component\HttpKernel\Exception
     * @throws \GetCandy\Exceptions\InvalidLanguageException
     *
     * @return Product
     */
    public function update($hashedId, array $data)
    {
        $product = $this->getByHashedId($hashedId);

        if (! $product) {
            abort(404);
        }

        $product->attribute_data = $data['attributes'];

        if (! empty($data['family_id'])) {
            $family = app('api')->productFamilies()->getByHashedId($data['family_id']);
            $family->products()->save($product);
        } else {
            $product->save();
        }

        if (!empty($data['channels'])) {
            $channelData = [];
            foreach ($data['channels']['data'] as $channel) {
                $channelModel = app('api')->channels()->getByHashedId($channel['id']);
                $channelData[$channelModel->id] = [
                    'visible' => $channel['visible'],
                    'published_at' => \Carbon\Carbon::parse($channel['published_at']['date'])
                ];
            }
            $product->channels()->sync($channelData);
        }
        if (!empty($data['customer_groups'])) {
            $groupData = [];
            foreach ($data['customer_groups']['data'] as $group) {
                $groupModel = app('api')->customerGroups()->getByHashedId($group['id']);
                $groupData[$groupModel->id] = [
                    'visible' => $group['visible'],
                    'purchasable' => $group['purchasable']
                ];
            }
            $product->customerGroups()->sync($groupData);
        }
        return $product;
    }

    public function createUrl($hashedId, array $data)
    {
        $product = $this->getByHashedId($hashedId);

        $product->routes()->create([
            'locale' => $data['locale'],
            'slug' => $data['slug'],
            'default' => false
        ]);

        return $product;
    }

    /**
     * Creates a resource from the given data
     *
     * @throws \GetCandy\Exceptions\InvalidLanguageException
     *
     * @return Product
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
     * @return Model
     */
    public function createVariant(Product $product, array $data = [])
    {
        $data['attribute_data'] = $product->attribute_data;
        return $product->variants()->create($data);
    }


    /**
     * @param $hashedId
     * @return mixed
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
     * @return \Illuminate\Pagination\LengthAwarePaginator
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

    /**
     * Updates the collections for a product
     * @param  String  $id
     * @param  array  $data
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return Model
     */
    public function updateCollections($id, array $data)
    {
        $ids = [];

        $product = $this->getByHashedId($id);

        foreach ($data['collections'] as $attribute) {
            $ids[] = app('api')->collections()->getDecodedId($attribute);
        }

        $product->collections()->sync($ids);
        return $product;
    }
}
