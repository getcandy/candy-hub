<?php

namespace GetCandy\Api\Contracts;

interface ServiceContract
{
    /**
     * Creates a resource
     * @param  array  $data
     * @return Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Updates a resource
     * @param  string $hashedId
     * @param  array  $data [description]
     * @return Illuminate\Database\Eloquent\Model
     */
    public function update($hashedId, array $data);

    /**
     * Deletes a resource
     * @param  int $id
     * @return boolean
     */
    public function delete($id);
}
