<?php

namespace GetCandy\Api\Services;

abstract class BaseService
{
    /**
     * Returns model by a given hashed id
     * @param  string $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getByHashedId($id)
    {
        $id = $this->model->decodeId($id);
        $result = $this->model->findOrFail($id);
        return $result;
    }

    /**
     * Get a collection of models from given Hashed IDs
     * @param  array  $ids
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getByHashedIds(array $ids)
    {
        $parsedIds = [];
        foreach ($ids as $hash) {
            $parsedIds[] = $this->model->decodeId($hash);
        }
        return $this->model->find($parsedIds);
    }

    public function count()
    {
        return (bool) $this->model->select('id')->get()->count();
    }

    public function getDecodedId($hash)
    {
        return $this->model->decodeId($hash);
    }

    public function getDefaultRecord()
    {
        return $this->model->where('default', '=', true)->first();
    }

    public function getPaginatedData($length = 50, $page = null)
    {
        return $this->model->paginate($length, ['*'], 'page', $page);
    }

    public function getNewSuggestedDefault()
    {
        return $this->model->where('default', '=', false)->where('enabled', '=', true)->first();
    }
}
