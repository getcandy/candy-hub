<?php

namespace GetCandy\Http\Middleware\Hub;

use Closure;
use Illuminate\Auth\AuthenticationException;

class HubAccess
{
    public function handle($request, Closure $next)
    {
        $roles = app('api')->roles()->getHubAccessRoles();
        if (!$request->user()->hasAnyRole($roles)) {
            abort(400, 'Unauthorised');
        }
        return $next($request);
    }
}
