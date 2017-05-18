<?php

namespace GetCandy\Api\Products;

use GetCandy\Api\Products\Services\ProductService;

class ProductManager
{
    /**
     * @var ProductService
     */
    protected $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function service()
    {
        return $this->productService;
    }
}
