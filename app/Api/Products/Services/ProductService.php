<?php

namespace GetCandy\Api\Products\Services;

use GetCandy\Api\Products\Models\Product;
use GetCandy\Api\Categories\Models\Category;
use GetCandy\Api\Products\Models\ProductVariant;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\InvalidLanguageException;
use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Search\SearchContract;
use GetCandy\Events\Products\ProductCreatedEvent;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductService extends BaseService
{
    protected $model;

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
                    'published_at' => $channel['published_at'] ? Carbon::parse($channel['published_at']) : null
                ];
            }
            $product->channels()->sync($channelData);
        }
        if (!empty($data['customer_groups'])) {
            $groupData = $this->mapCustomerGroupData($data['customer_groups']['data']);
            $product->customerGroups()->sync($groupData);
        }
        return $product;
    }

    /**
     * Maps customer group data for a product
     * @param  array $groups
     * @return array
     */
    protected function mapCustomerGroupData($groups)
    {
        $groupData = [];
        foreach ($groups as $group) {
            $groupModel = app('api')->customerGroups()->getByHashedId($group['id']);
            $groupData[$groupModel->id] = [
                'visible' => $group['visible'],
                'purchasable' => $group['purchasable']
            ];
        }
        return $groupData;
    }

    /**
     * Creates a URL for a product
     * @param  string $hashedId
     * @param  array  $data
     * @return Model
     */
    public function createUrl($hashedId, array $data)
    {
        $product = $this->getByHashedId($hashedId);

        $product->routes()->create([
            'locale' => $data['locale'],
            'slug' => $data['slug'],
            'description' => !empty($data['description']) ? $data['description'] : null,
            'redirect' => !empty($data['redirect']) ? true : false,
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

        $mapping = $product->getDataMapping();

        foreach ($mapping as $key => $map) {
            $locale = key($data['name']);
            $mapping[$key][$locale] = $data['name'][$locale];
        }

        $product->attribute_data = ['name' => $mapping];
        $product->option_data = [];

        // $layout = app('api')->layouts()->getByHashedId($data['layout_id']);
        // $product->layout()->associate($layout);

        if (! empty($data['family_id'])) {
            $family = app('api')->productFamilies()->getByHashedId($data['family_id']);
            if (! $family) {
                abort(422);
            }
            $family->products()->save($product);
        } else {
            $product->save();
        }

        $product->routes()->create([
            'locale' => app()->getLocale(),
            'slug' => $data['url'],
            'description' => !empty($data['description']) ? $data['description'] : null,
            'redirect' => !empty($data['redirect']) ? true : false,
            'default' => true
        ]);

        $this->createVariant($product, [
            'options' => [],
            'stock' => $data['stock'],
            'sku' => $data['sku'],
            'price' => $data['price']
        ]);

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

    public function saveOptions(array $data = [])
    {
    }

    /**
     * @param $hashedId
     * @return mixed
     */
    public function delete($hashedId)
    {
        return $this->getByHashedId($hashedId)->delete();
    }

    /**
     * Gets paginated data for the record
     * @param  integer $length How many results per page
     * @param  int  $page   The page to start
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedData($channel = null, $length = 50, $page = null)
    {
        $results = $this->model->channel($channel);
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
     * Gets the attributes from a given products id
     * @param  string $id
     * @return array
     */
    public function getCategories($product)
    {
        $product = $this->model
            ->with(['categories', 'routes'])
            ->find($product->id);

        return $product->categories;
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
