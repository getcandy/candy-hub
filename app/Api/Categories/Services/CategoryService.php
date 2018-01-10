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

        //TODO: REMOVE AS FROM IMPORT
        if (!empty($data['historical_id'])) {
            $category->id = $data['historical_id'];
        }
        
        $category->save();

        event(new AttributableSavedEvent($category));

        if (!empty($data['customer_groups'])) {
            $groupData = $this->mapCustomerGroupData($data['customer_groups']['data']);
            $category->customerGroups()->sync($groupData);
        }

        if (!empty($data['channels']['data'])) {
            $category->channels()->sync(
                $this->getChannelMapping($data['channels']['data'])
            );
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
        $category = $this->getByHashedId($id);

        // Remove all associations
        foreach ($category->children as $child) {
            $child->customerGroups()->detach();
            $child->products()->sync([]);
            $child->customerGroups()->sync([]);
            $child->channels()->sync([]);
            $child->delete();
        }
    
        $category->customerGroups()->detach();
        $category->products()->sync([]);
        $category->customerGroups()->sync([]);
        $category->channels()->sync([]);

        return $category->delete();
    }
}
