<?php

namespace GetCandy\Search;

use Illuminate\Database\Eloquent\Model;

interface ClientContract
{
    /**
     * Searches using the given keywords
     * @param  string $keywords
     */
    public function search($keywords);
}
