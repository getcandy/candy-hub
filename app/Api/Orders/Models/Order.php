<?php
namespace GetCandy\Api\Orders\Models;

use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Traits\HasCompletion;
use GetCandy\Api\Baskets\Models\Basket;
use Illuminate\Database\Eloquent\Builder;
use GetCandy\Api\Payments\Models\Transaction;

class Order extends BaseModel
{
    protected $hashids = 'order';

    protected $fillable = [
        'lines'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->where('status', '=', 'open');
        });

        static::addGlobalScope('not_expired', function (Builder $builder) {
            $builder->where('status', '!=', 'expired');
        });
    }

    /**
     * Gets the shipping details
     *
     * @return array
     */
    public function getShippingDetailsAttribute()
    {
        return $this->getDetails('shipping');
    }

    /**
     * Gets back the billing details
     *
     * @return array
     */
    public function getBillingDetailsAttribute()
    {
        return $this->getDetails('billing');
    }

    /**
     * Gets the details, mainly for contact info
     *
     * @param string $type
     * 
     * @return array
     */
    protected function getDetails($type)
    {
        return collect($this->attributes)->filter(function ($value, $key) use ($type) {
            return strpos($key, $type . '_') === 0;
        })->mapWithKeys(function ($item, $key) use ($type) {
            $newkey = str_replace($type . '_', '', $key);
            return [$newkey => $item];
        })->toArray();
    }

    /**
     * Get the basket lines
     *
     * @return void
     */
    public function lines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function basket()
    {
        return $this->belongsTo(Basket::class);
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
