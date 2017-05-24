<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\Tax;
use GetCandy\Api\Repositories\Eloquent\TaxRepository;
use GetCandy\Api\Exceptions\MinimumRecordRequiredException;

class TaxService extends BaseService
{
    /**
     * @var GetCandy\Api\Repositories\TaxRepository
     */
    protected $repo;


    public function __construct(TaxRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Models\Tax
     */
    public function create($data)
    {
        $tax = new Tax();
        $tax->name = $data['name'];
        $tax->percentage = $data['percentage'];

        if (empty($data['default']) && !$this->repo->hasRecords()) {
            $tax->default = true;
        } else {
            $tax->default = false;
        }

        if (!empty($data['default'])) {
            $this->setNewDefault($tax);
        }

        $tax->save();
        return $tax;
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $id
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception
     *
     * @return GetCandy\Api\Models\Tax
     */
    public function update($id, $data)
    {
        $tax = $this->repo->getByHashedId($id);

        if (!$tax) {
            abort(404);
        }

        if (!empty($data['default'])) {
            $this->setNewDefault($tax);
        }

        $tax->fill($data);
        $tax->save();
        return $tax;
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
        $tax = $this->repo->getByHashedId($id);

        if (!$tax) {
            abort(404);
        }

        if ($tax->default) {
            $newDefault = $this->repo->getNewSuggestedDefault();
            $this->setNewDefault($newDefault);
            $newDefault->save();
        }

        return $tax->delete();
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
