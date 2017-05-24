<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\Language;
use GetCandy\Api\Repositories\Eloquent\LanguageRepository;
use GetCandy\Exceptions\MinimumRecordRequiredException;

class LanguageService extends BaseService
{
    /**
     * @var GetCandy\Api\Repositories\LanguageRepository
     */
    protected $repo;

    public function __construct(LanguageRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Models\Language
     */
    public function create($data)
    {
        $language = new Language();
        $language->name = $data['name'];
        $language->code = $data['code'];
        if ((empty($data['default']) && !$this->repo->hasRecords()) || !empty($data['default'])) {
            $this->setNewDefault($language);
        }

        $language->save();

        return $language;
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $id
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception
     * @throws GetCandy\Api\Exceptions\MinimumRecordRequiredException
     *
     * @return GetCandy\Api\Models\Language
     */
    public function update($hashedId, $data)
    {
        $language = $this->repo->getByHashedId($hashedId);

        if (!$language) {
            abort(404);
        }

        if (!empty($data['name'])) {
            $language->name = $data['name'];
        }

        if (!empty($data['code'])) {
            $language->code = $data['code'];
        }

        if (!empty($data['default'])) {
            $this->setNewDefault($language);
        }

        if ((isset($data['enabled']) && !$data['enabled']) && $language->default) {
            // If we only have one record and we are trying to disable it, throw an exception
            if ($this->repo->getEnabled()->count() == 1) {
                throw new MinimumRecordRequiredException(
                    trans('getcandy_api::response.error.minimum_record')
                );
            }
            $newDefault = $this->repo->getNewSuggestedDefault();
            $this->setNewDefault($newDefault);
            $newDefault->save();
        }

        $language->save();

        return $language;
    }

    /**
     * Deletes a resource by its given hashed ID
     *
     * @param  string $id
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws GetCandy\Api\Exceptions\MinimumRecordRequiredException
     *
     * @return Boolean
     */
    public function deleteByHashedId($id)
    {
        $language = $this->repo->getByHashedId($id);

        if (!$language) {
            abort(404);
        }

        if ($this->repo->getEnabled()->count() == 1) {
            throw new MinimumRecordRequiredException(
                trans('getcandy_api::response.error.minimum_record')
            );
        }

        if ($language->default && $newDefault = $this->repo->getNewSuggestedDefault()) {
            $newDefault->default = true;
            $newDefault->save();
        }

        return $language->delete();
    }

    protected function setNewDefault(&$model)
    {
        if ($current = $this->repo->getDefaultRecord()) {
            $current->default = false;
            $current->save();
        }
        $model->default = true;
    }
}
