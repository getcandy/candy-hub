<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Repositories\Eloquent\LanguageRepository;
use GetCandy\Api\Scaffold\BaseService;
use GetCandy\Exceptions\DuplicateValueException;

class LanguageService extends BaseService
{
    /**
     * @var LanguageRepository
     */
    protected $repo;

    public function __construct(
        LanguageRepository $repo
    ) {
        $this->repo = $repo;
    }
}
