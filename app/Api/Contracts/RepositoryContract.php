<?php

namespace GetCandy\Api\Contracts;

interface RepositoryContract
{
    /**
     * Returns all records
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getAll();

    /**
     * Gets a resource by its Hashed ID
     * @param  String $id The hashed id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getByHashedId($id);

    /**
     * Get a collection of models from given Hashed IDs
     * @param  array  $ids
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function getByHashedIds(array $ids);

    /**
     * Return paginated results
     * @param  Int $length The amount per page
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginatedResults($length);
}
