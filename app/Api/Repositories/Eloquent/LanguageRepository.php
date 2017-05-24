<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Contracts\RepositoryContract;
use GetCandy\Api\Models\Language;

class LanguageRepository extends BaseRepository implements RepositoryContract
{
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
