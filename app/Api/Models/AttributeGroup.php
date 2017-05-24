<?php

namespace GetCandy\Api\Models;

use GetCandy\Api\Traits\Hashids;
use Illuminate\Database\Eloquent\Model;

class AttributeGroup extends Model
{
    use Hashids;

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
