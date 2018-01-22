<?php

namespace GetCandy\Http\Transformers\Fractal\Users;

use GetCandy\Http\Transformers\Fractal\Customers\CustomerGroupTransformer;
use GetCandy\Api\Auth\Models\User;
use League\Fractal\TransformerAbstract;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Http\Transformers\Fractal\Addresses\AddressTransformer;
use GetCandy\Http\Transformers\Fractal\Languages\LanguageTransformer;

class UserTransformer extends BaseTransformer
{
    protected $defaultIncludes = [
        'language'
    ];

    protected $availableIncludes = [
        'store', 'addresses', 'groups', 'roles'
    ];

    public function transform(User $user)
    {
        return [
            'id' => $user->encodedId(),
            'title' => $user->title,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'company_name' => $user->company_name,
            'contact_number' => $user->contact_number,
            'vat_no' => $user->vat_no,
            'email' => $user->email
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

    public function includeGroups(User $user)
    {
        return $this->collection($user->groups, new CustomerGroupTransformer);
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new UserRoleTransformer);
    }
}
