<?php

namespace GetCandy\Api\Collections\Services;

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
        $chancollectionnel = $this->model;
        $collection->attribute_data = $data['attributes'];
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
        $collection->fill($data);
        $collection->save();
        return $collection;
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
}
