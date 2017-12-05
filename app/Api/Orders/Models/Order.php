<?php
namespace GetCandy\Api\Orders\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Traits\HasCompletion;

class Order extends BaseModel
{
    protected $hashids = 'order';

    protected $fillable = [
        'lines'
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
