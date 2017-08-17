<?php

namespace GetCandy\Api\Search\Services;

use GetCandy\Api\Scaffold\BaseService;

class SearchService
{

    public function internalSearch($data)
    {
        if (empty($data['keywords'])) {
            return [];
        }
        $results = app('api')->products()->fuzzySearch($data['keywords'], 'attribute_data');
        return $results;
    }
}
