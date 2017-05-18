<?php

namespace GetCandy\Api\Products;

use GetCandy\Api\Products\Services\ProductService;

class ProductManager
{
    /**
     * @var ProductService
     */
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function getPaginatedResults($length = 50)
    {
        return $this->productService->getPaginatedResults($length);
    }

    public function update($id, array $data)
    {
        return $this->productService->update($id, $data);
    }

    public function create(array $data)
    {
        return $this->productService->create($data);
    }

    public function delete($id)
    {
        return $this->productService->deleteByHashedId($id);
    }
}
