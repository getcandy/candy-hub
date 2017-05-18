<?php

namespace GetCandy\Api\Languages;

use GetCandy\Api\Languages\Repositories\LanguageRepository;

class LanguageManager
{
    /**
     * @var LanguageRepository
     */
    protected $languageRepo;

    public function __construct(LanguageRepository $languageRepo)
    {
        $this->languageRepo = $languageRepo;
    }

    public function existsByCode($code)
    {
        return $this->languageRepo->existsByCode($code);
    }
}
