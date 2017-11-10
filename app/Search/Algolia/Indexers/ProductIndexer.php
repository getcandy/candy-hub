<?php

namespace GetCandy\Search\Algolia\Indexers;

use GetCandy\Api\Products\Models\Product;
use Elastica\Document;
use GetCandy\Search\Algolia\Indexable;

class ProductIndexer extends BaseIndexer
{
    /**
     * @var Product
     */
    protected $model = Product::class;

    /**
     * @var string
     */
    public $type = 'products';

    /**
     * Returns the Index document ready to be added
     * @param  Product $product
     * @return Document
     */
    public function getIndexables(Product $product)
    {
        $attributes = $this->attributeMapping($product);

        $indexables = collect();

        foreach ($attributes as $attribute) {
            foreach ($attribute as $lang => $item) {
                $indexable = new Indexable($item['data']['id']);
                $indexable->setIndex($item['index']);
                $indexable->setData($item['data']);
                $indexable->set('objectID', $indexable->getId());
                foreach ($product->variants as $variant) {
                    if (!$indexable->min_price || $indexable->min_price > $variant->price) {
                        $indexable->set('min_price', $variant->price);
                    }
                    if (!$indexable->max_price || $indexable->max_price > $variant->price) {
                        $indexable->set('max_price', $variant->price);
                    }
                    foreach ($variant->options as $handle => $option) {
                        if (!empty($option[$lang])) {
                            $indexable->add($handle, $option[$lang]);
                        }
                    }
                }
                $indexables->push($indexable);
            }
        }
        return $indexables;
    }

    public function rankings()
    {
        return [
            "name^5", "name.english^4"
        ];
    }

    /**
     * Returns the mapping used by elastic search
     * @return array
     */
    public function mapping()
    {
        return [
            'name' => [
                'type' => 'string',
                'analyzer' => 'standard',
                'fields' => [
                    'english' => [
                        'type' => 'string',
                        'analyzer' => 'english'
                    ]
                ]
            ]
        ];
    }
}
