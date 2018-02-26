<?php

namespace GetCandy\Importers\Aqua\Models\Users;

use GetCandy\Importers\Aqua\Models\BaseModel;
use GetCandy\Importers\Aqua\Models\UserGroups\UserGroup;

class UserProfile extends BaseModel
{
    protected $table = 'cscart_user_profiles';

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        return [
            'shipping' => [
                'firstname' => $this->s_firstname,
                'lastname' => $this->s_lastname,
                'address' => $this->s_address,
                'address_two' => $this->s_address_2,
                'city' => $this->s_city,
                'state' => $this->s_state,
                'country' => $this->s_country,
                'zip' => $this->s_zipcode,
                'shipping' => true
            ],
            'billing' => [
                'firstname' => $this->b_firstname,
                'lastname' => $this->b_lastname,
                'address' => $this->b_address,
                'address_two' => $this->b_address_2,
                'city' => $this->b_city,
                'state' => $this->b_state,
                'country' => $this->b_country,
                'zip' => $this->b_zipcode,
                'billing' => true
            ]
        ];
    }
}
