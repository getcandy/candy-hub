<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Models\Currency;
use GetCandy\Api\Repositories\Eloquent\CurrencyRepository;
use GetCandy\Api\Exceptions\MinimumRecordRequiredException;

class CurrencyService extends BaseService
{
    /**
     * @var GetCandy\Api\Repositories\CurrencyRepository
     */
    protected $repo;

    public function __construct(CurrencyRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Models\Currency
     */
    public function create($data)
    {
        $currency = new Currency();

        $currency->name = $data['name'];
        $currency->code = $data['code'];
        $currency->enabled = $data['enabled'];
        $currency->enabled = (bool) $data['enabled'];
        $currency->format = $data['format'];
        $currency->exchange_rate = $data['exchange_rate'];

        if (!empty($data['decimal_point'])) {
            $currency->decimal_point = $data['decimal_point'];
        }
        if (!empty($data['thousand_point'])) {
            $currency->thousand_point = $data['thousand_point'];
        }

        if (empty($data['default']) && !$this->repo->hasRecords()) {
            $currency->default = true;
        }

        if (!empty($data['default'])) {
            $this->setNewDefault($currency);
        }

        $currency->save();

        return $currency;
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
     * @return GetCandy\Api\Models\Currency
     */
    public function update($id, $data)
    {
        $currency = $this->currencyRepo->getByHashedId($id);

        if (!$currency) {
            abort(404);
        }

        $currency->fill($data);

        if (!empty($data['default'])) {
            $this->setNewDefault($currency);
        }

        if ((isset($data['enabled']) && !$data['enabled']) && $currency->default) {
            // If we only have one record and we are trying to disable it, throw an exception
            if ($this->currencyRepo->getEnabled()->count() == 1) {
                throw new MinimumRecordRequiredException(
                    trans('getcandy_api::response.error.minimum_record')
                );
            }
            $newDefault = $this->currencyRepo->getNewSuggestedDefault();
            $this->setNewDefault($newDefault);
            $newDefault->save();
        }

        $currency->save();

        return $currency;
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
        $currency = $this->currencyRepo->getByHashedId($id);

        if (!$currency) {
            abort(404);
        }

        if ($this->currencyRepo->getEnabled()->count() == 1) {
            throw new MinimumRecordRequiredException(
                trans('getcandy_api::response.error.minimum_record')
            );
        }

        $currency->enabled = false;
        $currency->save();

        if ($currency->default) {
            $newDefault = $this->currencyRepo->getNewSuggestedDefault();
            $this->setNewDefault($newDefault);
            $newDefault->save();
        }

        return $currency->delete();
    }

    protected function setNewDefault(&$model)
    {
        if ($current = $this->currencyRepo->getDefaultRecord()) {
            $current->default = false;
            $current->save();
        }
        $model->default = true;
    }
}
