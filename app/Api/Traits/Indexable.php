<?php

namespace GetCandy\Api\Traits;

use Elastica\Client;
use Elastica\Type\Mapping;
use Elastica\Document;
use Elastica\Status;

trait Indexable
{
    protected $client;

    public function addToIndex()
    {
        $this->name = json_decode($this->name, true)['en'];
        $doc = new Document($this->id, $this->toArray());
        $index = $this->getIndex($this->index);
        $elasticaType = $index->getType('eloquent');
        $response = $elasticaType->addDocument($doc);
    }

    public function createIndex()
    {
        $index = $this->client()->getIndex($this->index);
        $index->create();
    }

    public function getIndex()
    {
        $index = $this->client()->getIndex($this->index);

        $elasticaStatus = new Status($this->client());

        if (! $elasticaStatus->indexExists($this->index) and ! $elasticaStatus->aliasExists($this->index)) {
            // Requested index does not exist
            $index->create();
            // ...and create it's alias name
            //$index->addAlias($this->indexName);
            // ...and update the mappings
            $this->updateMappings($index);
        }
        return $index;
    }

    public function client()
    {
        if (! $this->client) {
            return new Client();
        }
        return $this->client;
    }

    public function updateMappings($index)
    {
        // Product mappings
        $elasticaType = $index->getType('eloquent');

        $mapping = new Mapping();
        $mapping->setType($elasticaType);

        $mapping->setProperties($this->mapping);
        $mapping->send();
    }

    public function deleteIndex()
    {
        $index = $this->client()->getIndex($this->index);

        $elasticaStatus = new Status($this->client());

        if ($elasticaStatus->indexExists($this->index) or $elasticaStatus->aliasExists($this->index)) {
            $index = $this->getIndex($this->index);
        }
    }
}
