<?php
namespace GetCandy\Http\Transformers\Fractal\Discounts;

use Carbon\Carbon;
use GetCandy\Api\Discounts\Models\Discount;
use GetCandy\Api\Discounts\Models\DiscountCriteriaItem;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class DiscountItemTransformer extends BaseTransformer
{
    public function transform(DiscountCriteriaItem $item)
    {
        return [
            'id' => $item->encodedId(),
            'type' => $item->type,
            'criteria' => json_decode($item->criteria, true)
        ];
    }
}
