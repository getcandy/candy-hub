<?php

namespace GetCandy\Importers\Aqua\Models\UserGroups;

use GetCandy\Importers\Aqua\Models\BaseModel;

class UserGroup extends BaseModel
{
    protected $table = 'cscart_usergroups';

    public function descriptions()
    {
        return $this->hasMany(UserGroupDescription::class, 'usergroup_id', 'usergroup_id');
    }
}
