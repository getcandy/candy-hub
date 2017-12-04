<?php

namespace GetCandy\Api\Categories\Services;

use Carbon\Carbon;
use GetCandy\Api\Attributes\Events\AttributableSavedEvent;
use GetCandy\Api\Categories\Models\Category;
use GetCandy\Api\Routes\Models\Route;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\MinimumRecordRequiredException;
use GetCandy\Search\SearchContract;

class CategoryService extends BaseService
{
    /**
     * @var AttributeGroup
     */
    protected $model;
    protected $route;

    public function __construct()
    {
        $this->model = new Category();
        $this->route = new Route();
    }

    public function getNestedList()
    {
        $categories = $this->model->withDepth()->defaultOrder()->get()->toTree();
        return $categories;
    }

    public function getByParentID($encodedParentID)
    {
        $parentID = $this->model->decodeId($encodedParentID);

        $categories = $this->model->where('parent_id', $parentID)->defaultOrder()->get();

        return $categories;
    }

    public function create(array $data)
    {
        // Create Category
        $category = $this->model;

        $category->attribute_data = $data;
        $category->id = $data['historical_id'];

        $category->save();

        event(new AttributableSavedEvent($category));

        if (!empty($data['customer_groups'])) {
            $groupData = $this->mapCustomerGroupData($data['customer_groups']['data']);
            $category->customerGroups()->sync($groupData);
        }

        $urls = $this->getUniqueUrl($data['url']);

        $category->routes()->createMany($urls);

        // If a parent id exists then add the category to the parent
        if (!empty($data['parent']['id'])) {
            $parentNode = $this->getByHashedId($data['parent']['id']);
            $parentNode->prependNode($category);
        }
        return $category;
    }

    public function update($hashedId, array $data)
    {
        $category = $this->getByHashedId($hashedId);
        $category->attribute_data = $data['attributes'];

        if (!empty($data['channels'])) {
            $channelData = [];
            foreach ($data['channels']['data'] as $channel) {
                $channelModel = app('api')->channels()->getByHashedId($channel['id']);
                $channelData[$channelModel->id] = [
                    'published_at' => $channel['published_at'] ? Carbon::parse($channel['published_at']) : null
                ];
            }
            $category->channels()->sync($channelData);
        }

        if (!empty($data['customer_groups'])) {
            $groupData = $this->mapCustomerGroupData($data['customer_groups']['data']);
            $category->customerGroups()->sync($groupData);
        }

        $category->save();

        event(new AttributableSavedEvent($category));

        return $category;
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

    public function reorder(array $data)
    {
        $response = false;

        $node = $this->getByHashedId($data['node']);
        $movedNode = $this->getByHashedId($data['moved-node']);
        $action = $data['action'];

        switch ($action) {
            case 'before':
                $response = $movedNode->insertBeforeNode($node);
                break;
            case 'after':
                $response = $movedNode->insertAfterNode($node);
                break;
            case 'over':
                $response = $node->prependNode($movedNode);
                break;
        }

        return $response;
    }

    public function uniqueAttribute($key, $value, $channel = 'ecommerce', $lang = 'en')
    {
        $categories = $this->model->get();

        foreach ($categories as $category) {
            if (isset(
                $category->attribute_data[$key][$channel][$lang]
            ) &&
                $category->attribute_data[$key][$channel][$lang] == $value
            ) {
                return false;
            }
        }
        return true;
    }

    public function getPaginatedData($length = 50, $page = null, $includes = [])
    {
        $results = $this->model->whereDoesntHave('parent');
        return $results->paginate($length, ['*'], 'page', $page);
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
}
