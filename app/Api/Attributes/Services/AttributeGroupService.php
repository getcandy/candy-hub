<?php

namespace GetCandy\Api\Attributes\Services;

use DB;
use GetCandy\Exceptions\DuplicateValueException;
use GetCandy\Api\Attributes\Models\AttributeGroup;
use GetCandy\Api\Attributes\Repositories\AttributeGroupRepository;
use GetCandy\Api\Scaffold\BaseService;

class AttributeGroupService extends BaseService
{
    /**
     * @var GetCandy\Api\Repositories\AttributeGroupRepository
     */
    protected $repo;

    public function __construct(AttributeGroupRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Models\Attribute
     */
    public function create(array $data)
    {
        $group = $this->repo->model();
        $group->name = $data['name'];
        $group->handle = str_slug($data['handle']);
        $group->position = $this->repo->count() + 1;
        $group->save();

        return $group;
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
     * @return GetCandy\Api\Models\AttributeGroup
     */
    public function update($hashedId, array $data)
    {
        $group = $this->repo->getByHashedId($hashedId);

        if (!$group) {
            return null;
        }

        $group->fill($data);
        $group->save();

        return $group;
    }

    /**
     * Updates the positions of attribute groups
     * @param  array  $data
     *
     * @throws Symfony\Component\HttpKernel\Exception\HttpException
     * @throws GetCandy\Api\Exceptions\DuplicateValueException
     *
     * @return Boolean
     */
    public function updateGroupPositions(array $data)
    {
        // Test for duplicates without hitting the database
        if (count($data['groups']) > count(array_unique($data['groups']))) {
            throw new DuplicateValueException(trans('getcandy_api::validation.attributes.groups.dupe_position'), 1);
        }

        $parsedGroups = [];

        foreach ($data['groups'] as $groupId => $position) {
            $decodedId = $this->repo->model()->decodeId($groupId);
            if (!$decodedId) {
                abort(422, trans('getcandy_api::validation.attributes.groups.invalid_id', ['id' => $groupId]));
            }
            $parsedGroups[$decodedId] = $position;
        }

        $groups = $this->repo->getByHashedIds(array_keys($data['groups']));

        foreach ($groups as $group) {
            $group->position = $parsedGroups[$group->id];
            $group->save();
        }

        return true;
    }

    /**
     * Deletes a resource by its given hashed ID
     *
     * @param  string $id
     *
     * @throws Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return Boolean
     */
    public function deleteByHashedId($id, $adopterId = null, $deleteAttributes = false)
    {
        $group = $this->repo->getByHashedId($id);

        if (!$group) {
            abort(404);
        }

        if (!$adopterId && !$deleteAttributes) {
            abort(400);
        }

        if ($adopterId) {
            $adopter = $this->repo->getByHashedId($adopterId);
            if (!$adopter) {
                abort(422);
            }
            $adopter->attributes()->saveMany($group->attributes);
        }

        foreach ($group->attributes()->get() as $attribute) {
            $attribute->delete();
        }

        $group->delete();

        $i = 1;

        foreach ($this->repo->getAllByPosition() as $group) {
            $group->position = $i;
            $i++;
        }

        return true;
    }
}
