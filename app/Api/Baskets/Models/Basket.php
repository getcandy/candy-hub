<?php
namespace GetCandy\Api\Baskets\Models;

use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Discounts\Factory;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Orders\Models\Order;
use GetCandy\Api\Traits\HasCompletion;
use Illuminate\Database\Eloquent\Scope;
use GetCandy\Api\Discounts\Models\Discount;
use TaxCalculator;

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

    public function discounts()
    {
        return $this->belongsToMany(Discount::class)->withPivot('coupon');
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
        $factory = new Factory;
        $sets = app('api')->discounts()->parse($this->discounts);
        $applied = $factory->getApplied($sets, \Auth::user(), null, $this);
        $total = $factory->applyToBasket($applied, $this);
        return TaxCalculator::deduct($total);
    }

    public function getVatTotalAttribute()
    {
        return TaxCalculator::amount($this->total);
    }
    public function getWeightAttribute()
    {
        $weight = 0;
        foreach ($this->lines as $line) {
            $weight += (float) $line->variant->weight_value;
        }
        return $weight;
    }
}
