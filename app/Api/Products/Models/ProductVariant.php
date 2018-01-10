<?php

namespace GetCandy\Api\Products\Models;

use GetCandy\Api\Attributes\Models\Attribute;
use GetCandy\Api\Scaffold\BaseModel;
use GetCandy\Api\Traits\HasAttributes;
use GetCandy\Api\Assets\Models\Asset;
use GetCandy\Api\Taxes\Models\Tax;
use PriceCalculator;
use Facades\GetCandy\Api\Taxes\TaxCalculator;

class ProductVariant extends BaseModel
{
    use HasAttributes;
    /**
     * The Hashid Channel for encoding the id
     * @var string
     */
    protected $hashids = 'product';

    protected $fillable = ['options', 'price', 'sku', 'stock'];


    public function product()
    {
        return $this->belongsTo(Product::class)->withoutGlobalScopes();
    }

    public function getNameAttribute()
    {
        //TODO: Figure out a more dynamic way to do this
        $name = '';
        $localeUsed = 'en';
        $locale = app()->getLocale();
        $i = 0;

        foreach ($this->options as $handle => $option) {
            if (!empty($option[$locale])) {
                $localeUsed = $locale;
            } 
            $name .= $option[$localeUsed] . ($i == count($this->options) ? ', ' : '');
        }

        return $name;
    }

    public function getOptionsAttribute($val)
    {
        $values = [];
        $option_data = $this->product->option_data;

        foreach (json_decode($val, true) as $option => $value) {
            if (! empty($data = $option_data[$option])) {
                $values[$option] = $data['options'][$value]['values'];
            }
        }
        return $values;
    }

    protected function pricing()
    {
        $user = \Auth::user();

        //TODO: Refactor this to it's own service
        $groups = app('api')->users()->getCustomerGroups($user);

        $ids = [];

        foreach ($groups as $group) {
            $ids[] = $group->id;
        }

        $pricing = null;
        
        if (!$user && !$user->hasRole('admin')) {
            $pricing = $this->customerPricing()
                ->whereIn('customer_group_id', $ids)
                ->orderBy('price', 'asc')
                ->first();
        }
        
        if ($pricing) {
            $tax = $pricing->tax ? $pricing->tax->percentage : 0;
            $price = $pricing->price;
        } else {
            $tax = 0;
            $price = $this->price;
            if ($this->tax) {
                $tax = $this->tax->percentage;
            }
        }
        return PriceCalculator::get($price, $tax);
    }

    public function getTotalPriceAttribute()
    {
        return $this->pricing()->amount;
    }

    public function getTaxTotalAttribute()
    {
        return $this->pricing()->tax;
    }

    public function setOptionsAttribute($val)
    {
        $options = [];
        foreach ($val as $option => $value) {
            if (is_array($value)) {
                $value = reset($value);
            }
            $options[str_slug($option)] = str_slug($value);
        }
        $this->attributes['options'] = json_encode($options);
    }

    public function image()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function customerPricing()
    {
        return $this->hasMany(ProductCustomerPrice::class);
    }

    public function tiers()
    {
        return $this->hasMany(ProductPricingTier::class);
    }
}
