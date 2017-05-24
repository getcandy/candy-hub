<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Contracts\ServiceContract;
use GetCandy\Api\Repositories\Eloquent\LanguageRepository;
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

    public function create(array $data)
    {
        //
    }

    public function update($hashedId, array $data)
    {
        //
    }

    public function delete($id)
    {
        //
    }
}
