<?php

return [
    'client' => \GetCandy\Search\Elastic\Elastic::class,
    'index_prefix' => env('SEARCH_INDEX_PREFIX'),
    'algolia' => [
        'app_key' => env('ALGOLIA_KEY'),
        'app_id' => env('ALGOLIA_ID')
    ]
];
