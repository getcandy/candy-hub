<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Repositories\Eloquent\LanguageRepository;
use GetCandy\Api\Contracts\ServiceContract;
use GetCandy\Exceptions\DuplicateValueException;

class LanguageService extends BaseService implements ServiceContract
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
