<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Models\User;
use GetCandy\Api\Support\Roles;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
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
