<?php

namespace GetCandy\Api\Languages\Repositories;

use GetCandy\Api\Languages\Models\Language;

class LanguageRepository
{
    /**
     * Determines whether a language exists by a given code
     * @param  string $code
     * @return boolean
     */
    public function existsByCode($code)
    {
        return Language::where('code', '=', $lang)->exists();
    }
}
