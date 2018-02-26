<?php

namespace GetCandy\Importers\Aqua\Models;

use GetCandy\Importers\Aqua\Models\BaseModel;

class Channel extends BaseModel
{
    protected $table = 'cscart_companies';

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    public function attributesToArray()
    {
        $name = $this->company;
        return [
            'name' => $name,
            'handle' => str_slug($name),
            'default' => str_slug($name) == 'aqua-spa-supplies' ? true : false
        ];
    }
}
