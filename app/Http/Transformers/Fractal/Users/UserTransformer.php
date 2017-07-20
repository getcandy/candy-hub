<?php

namespace GetCandy\Http\Transformers\Fractal\Users;

use GetCandy\Api\Auth\Models\User;
use League\Fractal\TransformerAbstract;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;

class UserTransformer extends BaseTransformer
{
    protected $availableIncludes = [
        'store'
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->encodedId(),
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ];
    }
}
