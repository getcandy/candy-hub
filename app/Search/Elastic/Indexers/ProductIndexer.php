<?php

namespace GetCandy\Search\Elastic\Indexers;

class ProductIndexer extends BaseIndexer
{
    /**
     * @var Product
     */
    protected $model = \GetCandy\Api\Models\Product::class;

    /**
     * @var string
     */
    public $type = 'product';

    /**
     * Adds the current model to the index
     */
    public function addToIndex()
    {
        $model = $this->model();
        $model->name = json_decode($model->name, true)['en'];
        $doc = new Document($model->id, $model->toArray());
        $index = $this->getIndex('getcandy');
        $elasticaType = $index->getType($this->type);
        $response = $elasticaType->addDocument($doc);
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
