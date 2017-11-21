<?php

namespace GetCandy\Search\Elastic\Indexers;

use GetCandy\Api\Products\Models\Product;
use Elastica\Document;

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
    public function getIndexDocument(Product $product)
    {
        return $this->getIndexables($product);
    }

    public function rankings()
    {
        return [
            "name.english^3", "description^1"
        ];
    }

    /**
     * Returns the mapping used by elastic search
     * @return array
     */
    public function mapping()
    {
        return [
            'id' => [
                'type' => 'string'
            ],
            'description' => [
                'type' => 'string',
                'analyzer' => 'standard',
            ],
            'departments' => [
                'type' => 'nested',
                'properties' => [
                    'id' => [
                        'type' => 'string',
                        'index' => 'not_analyzed'
                    ],
                    'name' => [
                        'type' => 'string'
                    ]
                ]
            ],
            'thumbnail' => [
                'type' => 'string'
            ],
            'min_price' => [
                "type" => "scaled_float",
                "scaling_factor" => 100
            ],
            'max_price' => [
                "type" => "scaled_float",
                "scaling_factor" => 100
            ],
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
