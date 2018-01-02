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

        $customerGroups = [];

        if ($this->usergroups()->count()) {
            foreach ($this->userGroups()->get() as $group) {
                $handle = str_slug($group->descriptions->first()->usergroup);
                $group = app('api')->customerGroups()->getByHandle($handle);
                $customerGroups['data'][] = [
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
                    $customerGroups['data'][] = [
                        'id' => $group->encodedId(),
                        'visible' => $this->status == 'A',
                        'purchasable' => $this->status == 'A'
                    ];
                }
            }
        }

        // French Categories
        $frenchCats = $this->where('company_id', '=', 2)->get();

        foreach ($frenchCats as $fCategory) {
            $frenchDescription = $fCategory->descriptions->first(function ($item, $key) {
                return $item->lang_code == 'fr';
            });

            $searchedName = $fCategory->descriptions->first(function ($item, $key) {
                return $item->lang_code == 'en';
            })->category;

            if ($this->isMatchedCategory($searchedName)) {
                // Get Current descriptions minus the french one...
                $descriptions = $this->descriptions->reject(function ($item) {
                    return $item->lang_code == 'fr';
                });
                $descriptions->add($frenchDescription);
                $this->descriptions = $descriptions;
            }
        }

        $channelData = [
            [
                'id' => app('api')->channels()->getByHandle('aqua-spa-supplies')->encodedId(),
                'published_at' => $this->status == 'A' ? \Carbon\Carbon::createFromTimestamp($this->timestamp) : null
            ],
            [
                'id' => app('api')->channels()->getByHandle('europe-aqua-spa-supplies')->encodedId(),
                'published_at' => $this->status == 'A' ? \Carbon\Carbon::createFromTimestamp($this->timestamp) : null
            ]
        ];

        return array_merge($decorator->getdata($this), ['channels' => ['data' => $channelData], 'customer_groups' => $customerGroups]);
    }

    protected function isMatchedCategory($name)
    {
        return (bool)$this->descriptions->first(function ($desc, $key) use ($name) {
            return $desc->lang_code == 'en' and $desc->category == $name;
        });
    }

    /**
     * foreach ($french as $fCategory) {
            // Get the french description...
            $frenchDescription = $fCategory->descriptions->first(function ($item, $key) {
                return $item->lang_code == 'fr';
            });

            // Do this so we can look for the right one to associate...
            $searchedName = $fCategory->descriptions->first(function ($item, $key) {
                return $item->lang_code == 'en';
            })->category;

            dump($frenchDescription->category, $searchedName);
            
            foreach ($english as $englishCategory) {
                if ($this->isMatchedCategory($englishCategory->descriptions, $frenchDescription)) {
                    // Get Current descriptions minus the french one...
                    $descriptions = $englishCategory->descriptions->reject(function ($item) {
                        return $item->lang_code == 'fr';
                    });
                    $descriptions->add($frenchDescription);
                    $englishCategory->descriptions = $descriptions;
                }
            }
        }
     */
}
