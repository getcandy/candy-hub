<?php

namespace GetCandy\Api\Auth\Services;

use GetCandy\Api\Auth\Models\User;
use GetCandy\Api\Scaffold\BaseService;

class UserService extends BaseService
{
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Creates a resource from the given data
     *
     * @param  array  $data
     *
     * @return GetCandy\Api\Auth\Models\User
     */
    public function create($data)
    {
        $user = new User();
        $user->password = bcrypt($data['password']);
        $user->name = $data['name'];
        $user->email = $data['email'];

        // Get the language.
        $language = app('api')->languages()->getEnabledByLang($data['language']);

        // Get the user group.
        if ($data['group']) {
            $group = app('api')->customerGroups()->getByHandle($data['group']);
        } else {
            $group = app('api')->customerGroups()->getDefaultRecord();
        }

        if (!empty($data['fields'])) {
            $user->fields = $data['fields'];
        }

        $user->save();

        $user->groups()->attach($group);
        $user->language()->associate($language);

        $user->save();

        return $user;
    }
}
