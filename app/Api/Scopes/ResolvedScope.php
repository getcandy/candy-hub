<?php

namespace GetCandy\Api\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ResolvedScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->whereNull('resolved_at');
    }
}
