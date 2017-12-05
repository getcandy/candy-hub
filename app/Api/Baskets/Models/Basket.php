<?php
namespace GetCandy\Api\Baskets\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Auth\Models\User;
use Illuminate\Database\Eloquent\Scope;
use GetCandy\Api\Traits\HasResolves;

class Basket extends BaseModel
{
    use HasResolves;

    protected $hashids = 'basket';

    protected $fillable = [
        'lines', 'resolved_at', 'merged'
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
