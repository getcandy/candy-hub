<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Contracts\RepositoryContract;
use GetCandy\Api\Models\Attribute;
use Illuminate\Http\Request;

class AttributeRepository extends BaseRepository implements RepositoryContract
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Attribute();
    }

    public function getAttributesForGroup($groupId)
    {
        return $this->model->where('group_id', '=', $groupId)->get();
    }

    public function getLastItem($groupId)
    {
        return $this->model->orderBy('position', 'desc')->where('group_id', '=', $groupId)->first();
    }

    public function nameExistsInGroup($value, $groupId, $attributeId = null)
    {
        $result = $this->model->where('name', '=', $value)
                        ->where('group_id', '=', $groupId);

        if ($attributeId) {
            $result->where('id', '!=', $attributeId);
        }

        return !$result->exists();
    }
}
