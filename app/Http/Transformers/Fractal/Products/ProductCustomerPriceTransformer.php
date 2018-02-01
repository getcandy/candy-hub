<?php

namespace GetCandy\Http\Transformers\Fractal\Products;

use GetCandy\Api\Products\Models\ProductFamily;
use GetCandy\Http\Transformers\Fractal\Attributes\AttributeTransformer;
use GetCandy\Http\Transformers\Fractal\Associations\AssociationGroupTransformer;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Products\Models\ProductAssociation;
use GetCandy\Api\Products\Models\ProductCustomerPrice;
use GetCandy\Http\Transformers\Fractal\Taxes\TaxTransformer;
use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;

class ProductCustomerPriceTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'tax', 'group'
    ];

    public function transform(ProductCustomerPrice $model)
    {
        return [
            'id' => $model->encodedId(),
            'price' => $model->price,
            'compare_at_price' => $model->compare_at_price0
        ];
    }

    public function includeTax(ProductCustomerPrice $price)
    {
        if (!$price->tax) {
            return $this->null();
        }
        return $this->item($price->tax, new TaxTransformer);
    }

    public function includeGroup(ProductCustomerPrice $price)
    {
        return $this->item($price->group, new CustomerGroupTransformer);
    }
}
