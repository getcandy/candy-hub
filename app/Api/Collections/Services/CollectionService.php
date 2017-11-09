<?php

namespace GetCandy\Api\Collections\Services;

use Carbon\Carbon;
use GetCandy\Api\Collections\Models\Collection;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\MinimumRecordRequiredException;

class CollectionService extends BaseService
{
    /**
     * @var AttributeGroup
     */
    protected $model;

    public function __construct()
    {
        $this->model = new Collection();
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return Collection
     */
    public function create(array $data)
    {
        $collection = $this->model;

        $mapping = $collection->getDataMapping();

        $attributes = app('api')->attributes()->getHandles();

        $attributeData = [];

        foreach ($attributes as $attribute) {
            if (!empty($data[$attribute])) {
                foreach ($mapping as $key => $map) {
                    $locale = key($data[$attribute]);
                    $mapping[$key][$locale] = $data[$attribute][$locale];
                }
                $attributeData[$attribute] = $mapping;
            }

        }

        $collection->attribute_data = $attributeData;
        $collection->save();
        return $collection;
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $id
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Collection
     */
    public function update($hashedId, array $data)
    {
        $collection = $this->getByHashedId($hashedId);
        $collection->attribute_data = $data['attributes'];

        if (!empty($data['channels'])) {
            $channelData = [];
            foreach ($data['channels']['data'] as $channel) {
                $channelModel = app('api')->channels()->getByHashedId($channel['id']);
                $channelData[$channelModel->id] = [
                    'published_at' => $channel['published_at'] ? Carbon::parse($channel['published_at']) : null
                ];
            }
            $collection->channels()->sync($channelData);
        }

        if (!empty($data['customer_groups'])) {
            $groupData = $this->mapCustomerGroupData($data['customer_groups']['data']);
            $collection->customerGroups()->sync($groupData);
        }

        $collection->save();
        return $collection;
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
     * Deletes a resource by its given hashed ID
     *
     * @param  string $id
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return Boolean
     */
    public function delete($id)
    {
        $collection = $this->getByHashedId($id);
        return $collection->delete();
    }

    /**
     * Creates a URL for a product
     * @param  string $hashedId
     * @param  array  $data
     * @return Model
     */
    public function createUrl($hashedId, array $data)
    {
        $collection = $this->getByHashedId($hashedId);

        $collection->routes()->create([
            'locale' => $data['locale'],
            'slug' => $data['slug'],
            'description' => !empty($data['description']) ? $data['description'] : null,
            'redirect' => !empty($data['redirect']) ? true : false,
            'default' => false
        ]);
        return $collection;
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
}
