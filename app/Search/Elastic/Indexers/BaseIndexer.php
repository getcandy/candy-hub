<?php

namespace GetCandy\Search\Elastic\Indexers;

use Elastica\Client;
use Elastica\Type\Mapping;
use Elastica\Document;
use Elastica\Status;

abstract class BaseIndexer
{
    protected $client;

    protected $model;

    public function model()
    {
        return $this->model;
    }

    /**
     * Adds the current model to the index
     */
    public function addToIndex()
    {
        $doc = new Document($this->model()->id, $this->model()->toArray());
        $index = $this->getIndex('getcandy');
        $elasticaType = $index->getType($this->type);
        $response = $elasticaType->addDocument($doc);
    }

    /**
     * Create an index based on the model
     * @return void
     */
    public function createIndex()
    {
        $index = $this->client()->getIndex('getcandy');
        $index->create();
    }

    /**
     * Returns the index for the model
     * @return Elastica\Index
     */
    public function getIndex()
    {
        $index = $this->client()->getIndex($this->index);

        $elasticaStatus = new Status($this->client());

        if (! $elasticaStatus->indexExists('getcandy') and ! $elasticaStatus->aliasExists('getcandy')) {
            // Requested index does not exist
            $index->create();
            // ...and create it's alias name
            //$index->addAlias($this->indexName);
            // ...and update the mappings
            $this->updateMappings($index);
        }
        return $index;
    }

    /**
     * Gets the client for the model
     * @return Elastica\Client
     */
    public function client()
    {
        if (! $this->client) {
            return new Client();
        }
        return $this->client;
    }

    /**
     * Updatess the mappings for the model
     * @param  Elastica\Index $index
     * @return void
     */
    public function updateMappings($index)
    {
        $elasticaType = $index->getType($this->type);

        $mapping = new Mapping();
        $mapping->setType($elasticaType);

        $mapping->setProperties($this->mapping());
        $mapping->send();
    }

    /**
     * Deletes the index
     * @return void
     */
    public function deleteIndex()
    {
        $index = $this->client()->getIndex($this->index);

        $elasticaStatus = new Status($this->client());

        if ($elasticaStatus->indexExists($this->index) or $elasticaStatus->aliasExists($this->index)) {
            $index = $this->getIndex($this->index);
        }
    }
}
