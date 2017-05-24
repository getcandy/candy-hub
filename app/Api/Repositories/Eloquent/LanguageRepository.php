<?php

namespace GetCandy\Api\Repositories\Eloquent;

use GetCandy\Api\Models\Language;

class LanguageRepository
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
