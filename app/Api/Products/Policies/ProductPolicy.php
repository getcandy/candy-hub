<?php

namespace GetCandy\Api\Products\Policies;

use GetCandy\Api\Products\Models\Product;

class ProductPolicy
{
    public function __construct()
    {
        dd('hit');
    }
    public function before()
    {   
        dd('hit');
        return true;
    }
    public function update(User $user, Product $product)
    {
        dd($product);
    }

    public function edit()
    {
        dd('hit');
    }
}
