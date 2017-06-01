<?php

namespace GetCandy\Api\Attributes\Models;

use GetCandy\Api\Scaffold\BaseModel;

class AttributeGroup extends BaseModel
{
    protected $hashids = 'attribute_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'position',
    ];

    /**
     * Get the attributes associated to the group
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id');
    }
}
