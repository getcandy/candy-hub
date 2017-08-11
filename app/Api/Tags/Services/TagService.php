<?php

namespace GetCandy\Api\Tags\Services;

use GetCandy\Api\Tags\Models\Tag;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\DuplicateValueException;

class TagService extends BaseService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tag();
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Models\Tag
     */
    public function create(array $data)
    {
        // TODO
        return true;
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $id
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return GetCandy\Api\Models\Tag
     */
    public function update($hashedId, array $data)
    {
        $tag = $this->getByHashedId($hashedId);

        if (!$tag) {
            abort(404);
        }

        $tag->fill($data);
        $tag->save();

        return $tag;
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
        $tag = $this->getByHashedId($id);


        if (!$tag) {
            abort(404);
        }

        return $tag->delete();
    }

    public function getTaggables(array $hashedIds, $type = null)
    {
        $ids = [];
        foreach ($hashedIds as $hash) {
            $ids[] = $this->model->decodeId($hash);
        }
        $query = $this->model->with(['taggables', 'taggables.records']);
        if ($type) {
            $query = $this->model->with(['taggables' => function ($query) use ($type) {
                $query->where('taggable_type', '=', $type);
            }, 'taggables.records']);
        }
        return $query->find($ids);
    }
}
