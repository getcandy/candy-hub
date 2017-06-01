<?php

namespace GetCandy\Search;

use Illuminate\Database\Eloquent\Model;

interface SearchContract
{
    // public function autoSuggest();
    // public function createIndex();
    // public function getIndex();
    // public function index($index);

    /**
     * Searches using the given keywords
     * @param  string $keywords
     */
    public function search($keywords);

    public function index(Model $model);
}
