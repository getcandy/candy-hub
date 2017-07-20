<?php

namespace GetCandy\Api\Scaffold;

use GetCandy\Api\Traits\Hashids;
use Illuminate\Database\Eloquent\Model;
use GetCandy\Api\Routes\Models\Route;

abstract class BaseModel extends Model
{
    use Hashids;

    /**
     * Scope a query to only include enabled.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', '=', true);
    }

    /**
     * Scope a query to only include the default record.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('default', '=', true);
    }

    public function routes()
    {
        return $this->morphMany(Route::class, 'element');
    }
}
