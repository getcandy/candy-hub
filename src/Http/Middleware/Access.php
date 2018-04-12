<?php

namespace GetCandy\Hub\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;

class Access
{
    public function handle($request, Closure $next)
    {
        $roles = app('api')->roles()->getHubAccessRoles();
        if (!$request->user()->hasAnyRole($roles)) {
            \Auth::logout();
            return redirect()->route('hub.login')->with('unauth', 'You are unauthorized to view this page');
        }
        return $next($request);
    }
}
