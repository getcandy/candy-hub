<?php
namespace GetCandy\Api\Traits;

use GetCandy\Api\Scopes\ResolvedScope;

trait HasResolves
{
    public static function boot()
    {
        static::addGlobalScope(new ResolvedScope);
    }
}
