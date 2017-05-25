<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\Language;
use GetCandy\Exceptions\MinimumRecordRequiredException;

class LanguageService extends BaseService
{
    public function __construct()
    {
        $this->model = new Language();
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
        if ((empty($data['default']) && !$this->model->count()) || !empty($data['default'])) {
            $this->setNewDefault($language);
        }

        $language->save();

        return $language;
    }

    public function getEnabledByCode($code)
    {
        return $this->model->where('code', '=', $code)->where('enabled', '=', true)->first();
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
        $language = $this->getByHashedId($hashedId);

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
            if ($this->model->enabled()->count() == 1) {
                throw new MinimumRecordRequiredException(
                    trans('getcandy_api::response.error.minimum_record')
                );
            }
            $newDefault = $this->getNewSuggestedDefault();
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
    public function delete($id)
    {
        $language = $this->getByHashedId($id);

        if (!$language) {
            abort(404);
        }

        if ($this->model->enabled()->count() == 1) {
            throw new MinimumRecordRequiredException(
                trans('getcandy_api::response.error.minimum_record')
            );
        }

        if ($language->default && $newDefault = $this->getNewSuggestedDefault()) {
            $newDefault->default = true;
            $newDefault->save();
        }

        return $language->delete();
    }

    /**
     * Determines whether a language exists by a given code
     * @param  string $code
     * @return boolean
     */
    public function existsByCode($code)
    {
        return $this->model->where('code', '=', $code)->exists();
    }

    protected function setNewDefault(&$model)
    {
        if ($current = $this->getDefaultRecord()) {
            $current->default = false;
            $current->save();
        }
        $model->default = true;
    }
}
