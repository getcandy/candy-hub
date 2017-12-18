<?php

namespace GetCandy\Http\Middleware;

use Closure;
use TaxCalculator;

class SetTaxMiddleware
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
        if ($request->no_vat) {
            TaxCalculator::setDeductable('VAT');
        }
        return $next($request);
    }
}
