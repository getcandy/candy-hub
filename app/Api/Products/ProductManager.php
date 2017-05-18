<?php

namespace GetCandy\Api\Products;

use GetCandy\Api\Products\Services\ProductService;
use GetCandy\Api\Products\Repositories\ProductRepository;

class ProductManager
{
    /**
     * @var ProductService
     */
    protected $productService;

    /**
     * @var ProductRepository
     */
    protected $productRepo;

    public function __construct(
        ProductService $productService,
        ProductRepository $productRepo
    ) {
        $this->productService = $productService;
        $this->productRepo = $productRepo;
    }

    public function getPaginatedResults($length = 50)
    {
        return $this->productRepo->getPaginatedResults($length);
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
