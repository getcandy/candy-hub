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

    protected function profiles()
    {
        return $this->hasMany(UserProfile::class, 'user_id', 'user_id');
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
            'id' => $this->user_id,
            'name' => $this->firstname . ' ' . $this->lastname,
            'company_name' => $this->company,
            'email' => $this->email,
            'language_id' => app('api')->languages()->getEnabledByLang($this->lang_code)->id,
            'password' => 'getcandyreset'
        ];  

        $groups = [];

        foreach ($this->groups as $group) {
            $model = app('api')->customerGroups()->getByHandle(str_slug($group->descriptions->first()->usergroup));
            $groups[] = $model->encodedId();
        }

        $data['customer_groups'] = $groups;

        return $data;
    }
}
