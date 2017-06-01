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
    public $type = 'product';

    /**
     * Returns the Index document ready to be added
     * @param  Product $product
     * @return Document
     */
    public function getIndexDocument(Product $product)
    {
        $data = $product->toArray();
        $data['name'] = json_decode($product->name, true)['en'];
        return new Document(
            $product->id,
            $data
        );
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
