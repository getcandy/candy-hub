<?php

namespace GetCandy\Api\Models;

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

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'group_id');
    }
}
