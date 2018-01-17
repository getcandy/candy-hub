<?php

namespace GetCandy\Http\Middleware;

use Closure;
use GetCandy;

class SetCustomerGroups
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            $groups = $request->user()->groups;
        } else {
            $groups = [app('api')->customerGroups()->getGuest()];
        }
        GetCandy::setGroups($groups);
        return $next($request);
    }
}
