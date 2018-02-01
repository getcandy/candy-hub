<?php

namespace GetCandy\Importers\Aqua\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    protected $connection = 'aquaspa';

    protected function getCustomerGroups()
    {
        $data = [];
        if ($this->usergroups()->count()) {
            foreach ($this->userGroups()->get() as $group) {
                $handle = str_slug($group->descriptions->first()->usergroup);
                $group = app('api')->customerGroups()->getByHandle($handle);
                $data[] = [
                    'id' => $group->encodedId(),
                    'visible' => $this->status == 'A',
                    'purchasable' => $this->status == 'A'
                ];
            }
        }
                // // Gotta try and get our guest ones out...
        foreach (explode(',', $this->usergroup_ids) as $groupId) {
            if ($groupId == 0) {
                $groups = \GetCandy\Api\Customers\Models\CustomerGroup::all();
                foreach ($groups as $group) {
                    $data[] = [
                        'id' => $group->encodedId(),
                        'visible' => $this->status == 'A',
                        'purchasable' => $this->status == 'A'
                    ];
                }
            }
        }

        return $data;
    }
}
