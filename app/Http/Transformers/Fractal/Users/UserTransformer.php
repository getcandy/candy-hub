<?php

namespace GetCandy\Http\Transformers\Fractal\Users;

use GetCandy\Api\Auth\Models\User;
use League\Fractal\TransformerAbstract;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Languages\LanguageTransformer;
use GetCandy\Http\Transformers\Fractal\Addresses\AddressTransformer;
use GetCandy\Http\Transformers\Fractal\Orders\OrderTransformer;

class UserTransformer extends BaseTransformer
{
    protected $defaultIncludes = [
        'language'
    ];

    protected $availableIncludes = [
        'addresses','orders','basket'
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->encodedId(),
            'name' => $user->name,
            'company_name' => $user->company_name,
            'email' => $user->email,
            'role' => $user->role,
            'fields' => $user->fields
        ];
    }

    public function includeLanguage(User $user)
    {
        return $this->item($user->language, new LanguageTransformer);
    }

    public function includeAddresses(User $user)
    {
        return $this->collection($user->addresses, new AddressTransformer);
    }
    
    public function includeOrders(User $user)
    {
        if (!$user->orders) {
            return $this->null();
        }
        return $this->collection($user->orders, new OrderTransformer);
    }
}
