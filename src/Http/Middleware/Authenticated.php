<?php

namespace GetCandy\Hub\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;

class Authenticated extends Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($guards);
        } catch (AuthenticationException $e) {
            return redirect()->route('hub.login');
        }
        return $next($request);
    }
}
