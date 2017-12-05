<?php
namespace GetCandy\Api\Baskets\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Auth\Models\User;

class Basket extends BaseModel
{
    protected $hashids = 'basket';

    protected $fillable = [
        'quantity'
    ];

    /**
     * Get the basket lines
     *
     * @return void
     */
    public function lines()
    {
        return $this->hasMany(BasketLine::class);
    }

    /**
     * Get the basket user
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
