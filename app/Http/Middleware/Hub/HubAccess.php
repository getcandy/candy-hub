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
            \Auth::logout();
            return redirect('login')->with('unauth', 'You are unauthorized to view this page');
        }
        return $next($request);
    }
}
