<?php

namespace GetCandy\Importers\Aqua\Models\Categories;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;
use GetCandy\Importers\Aqua\Decorator;

class Category extends BaseModel
{
    protected $table = 'cscart_categories';

    public $idref = 'category_id';

    public function descriptions()
    {
        return $this->hasMany(CategoryDescription::class, 'category_id', 'category_id');
    }
    public function scopeParents($query)
    {
        $query->whereParentId(0);
    }
    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany($this, 'parent_id', 'category_id');
    }

    public function usergroups()
    {
        // Convert column to an array...
        return UserGroup::whereIn('usergroup_id', explode(',', $this->usergroup_ids));
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $decorator = new Decorator;

        $customerGroups = [];

        if ($this->usergroups()->count()) {
            foreach ($this->userGroups()->get() as $group) {
                $handle = str_slug($group->descriptions->first()->usergroup);
                $group = app('api')->customerGroups()->getByHandle($handle);
                $customerGroups['data'][] = [
                    'id' => $group->encodedId(),
                    'visible' => true,
                    'purchasable' => true
                ];
            }
        }

        return array_merge($decorator->getdata($this), ['customer_groups' => $customerGroups]);
    }
}
