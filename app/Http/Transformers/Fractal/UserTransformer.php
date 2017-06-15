<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Auth\Models\User;
use League\Fractal\TransformerAbstract;

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
