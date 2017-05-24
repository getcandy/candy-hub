<?php

namespace GetCandy\Api\Services;

use GetCandy\Api\Contracts\ServiceContract;
use GetCandy\Api\Exceptions\MinimumRecordRequiredException;
use GetCandy\Api\Models\Channel;
use GetCandy\Api\Repositories\Eloquent\ChannelRepository;

class ChannelService extends BaseService implements ServiceContract
{
    /**
     * @var GetCandy\Api\Repositories\ChannelRepository
     */
    protected $repo;

    public function __construct(ChannelRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Models\Channel
     */
    public function create(array $data)
    {
        $channel = new Channel();
        $channel->name = $data['name'];

        // If this is the first channel, make it default
        if (empty($data['default']) && !$this->repo->hasRecords()) {
            $channel->default = true;
        }

        if (!empty($data['default'])) {
            $this->setNewDefault($channel);
        } else {
            $channel->default = false;
        }

        $channel->save();

        return $channel;
    }

    /**
     * Updates a resource from the given data
     *
     * @param  string $id
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws GetCandy\Api\Exceptions\MinimumRecordRequiredException
     *
     * @return GetCandy\Api\Models\Channel
     */
    public function update($hashedId, array $data)
    {
        $channel = $this->repo->getByHashedId($hashedId);

        if (!$channel) {
            return null;
        }

        $channel->fill($data);

        if (!empty($data['default'])) {
            $this->setNewDefault($channel);
        }

        $channel->save();

        return $channel;
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
        $channel = $this->repo->getByHashedId($id);

        if (!$channel) {
            abort(404);
        }

        if ($this->repo->model()->count() == 1) {
            throw new MinimumRecordRequiredException(
                trans('getcandy_api::response.error.minimum_record')
            );
        }

        if ($channel->default && $newDefault = $this->repo->model()->first()) {
            $newDefault->default = true;
            $newDefault->save();
        }

        return $channel->delete();
    }

    /**
     * Finds and sets a new default record based on whats available
     * @param GetCandy\Api\Models\Channel &$model
     */
    protected function setNewDefault(&$model)
    {
        if ($current = $this->repo->getDefaultRecord()) {
            $current->default = false;
            $current->save();
        }
        $model->default = true;
    }
}
