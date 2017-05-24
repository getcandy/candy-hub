<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Contracts\RepositoryContract;
use GetCandy\Api\Models\Language;

class LanguageRepository extends BaseRepository implements RepositoryContract
{

    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Language();
    }

    /**
     * Determines whether a language exists by a given code
     * @param  string $code
     * @return boolean
     */
    public function existsByCode($code)
    {
        return Language::where('code', '=', $code)->exists();
    }
}
