<?php

namespace GetCandy\Api\Auth\Models;

use GetCandy\Api\Addresses\Models\Address;
use GetCandy\Api\Baskets\Models\Basket;
use GetCandy\Api\Customers\Models\CustomerGroup;
use GetCandy\Api\Languages\Models\Language;
use GetCandy\Api\Traits\Hashids;
use GetCandy\Api\Traits\HasCustomerGroups;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,
        Hashids,
        HasApiTokens,
        HasRoles;

    protected $hashids = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',  'password', 'role', 'company_name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function groups()
    {
        return $this->belongsToMany(CustomerGroup::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function getFieldsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function basket()
    {
        return $this->hasOne(Basket::class);
    }

    public function setFieldsAttribute($value)
    {
        $this->attributes['fields'] = json_encode($value);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
