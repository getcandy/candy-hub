<?php

namespace GetCandy\Importers\Aqua\Models\Users;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class User extends BaseModel
{
    protected $table = 'cscart_users';

    protected function groups()
    {
        return $this->belongsToMany(UserGroup::class, 'cscart_usergroup_links', 'user_id', 'usergroup_id', 'user_id', 'usergroup_id');
    }

    // $related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null,
    //     $parentKey = null, $relatedKey = null, $relation = null

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $data = [
            'name' => $this->firstname . ' ' . $this->lastname,
            'company_name' => $this->company,
            'email' => $this->email,
            'lang' => $this->lang_code,
            'password' => 'getcandyreset'
        ];  

        $groups = [];

        foreach ($this->groups as $group) {
            // $groups[] = $this->
        }
    }
}
