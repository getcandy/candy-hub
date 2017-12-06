<?php
namespace GetCandy\Api\Baskets\Models;

use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasCompletion;
use Illuminate\Database\Eloquent\Scope;

class Basket extends BaseModel
{
    use HasCompletion;

    protected $hashids = 'basket';

    protected $fillable = [
        'lines', 'completed_at', 'merged_id'
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

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function getTotalAttribute()
    {
        $total = 0;
        foreach ($this->lines as $line) {
            $total += $line->total;
        }
        return $total;
    }
}
