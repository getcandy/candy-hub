<?php

namespace GetCandy\Api\Repositories;

use GetCandy\Api\Contracts\RepositoryContract;
use GetCandy\Api\Models\Channel;
use Illuminate\Http\Request;

class ChannelRepository extends BaseRepository implements RepositoryContract
{
    public function __construct()
    {
        $this->label = 'name';
        $this->model = new Channel();
    }
}
