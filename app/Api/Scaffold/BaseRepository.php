<?php

namespace GetCandy\Api\Scaffold;

abstract class BaseRepository
{
    /**
     * @var Object
     */
    protected $model;

    /**
     * @var String
     */
    protected $label;

    public function getNew()
    {
        return $this->model;
    }

    public function getAll()
    {
        $query = $this->model->orderBy($this->label, 'asc');
        return $query->get();
    }

    public function getByHashedId($id)
    {
        $id = $this->model->decodeId($id);
        $result = $this->model->find($id);

        if (!$result) {
            abort(404);
        }
        return $result;
    }

    public function getByHashedIds(array $ids)
    {
        $parsedIds = [];
        foreach ($ids as $hash) {
            $parsedIds[] = $this->model->decodeId($hash);
        }
        return $this->model->find($parsedIds);
    }

    public function getDefaultRecord()
    {
        return $this->model->where('default', '=', true)->first();
    }

    public function getPaginatedResults($length = 50)
    {
        $paginator = $this->model->paginate($length);
        return $paginator;
    }

    public function hasRecords()
    {
        return (bool) $this->model->select('id')->get()->count();
    }

    public function count()
    {
        return $this->model->count();
    }

    public function model()
    {
        return $this->model;
    }

    public function getNewSuggestedDefault()
    {
        return $this->model->where('default', '=', false)->where('enabled', '=', true)->first();
    }

    public function getEnabled()
    {
        return $this->model->where('enabled', '=', true)->get();
    }
}
