<?php

namespace GetCandy\Http\Middleware;

use Closure;
use GetCandy\Api\Traits\Fractal;

class SetLocaleMiddleware
{
    use Fractal;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->header('accept-language');

        if (!$locale) {
            $locale = app('api')->languages()->getDefaultRecord()->code;
        } elseif ($locale != 'en-us,en;q=0.5' && $locale != 'en-GB,en-US;q=0.8,en;q=0.6' && $locale != 'en-gb') {
            $requestedLocale = app('api')->languages()->getEnabledByCode($locale);
            if (!$requestedLocale) {
                return $this->errorWrongArgs(trans('getcandy_api::response.error.invalid_lang', ['lang' => $locale]));
            }
            $locale = $requestedLocale->code;
        } else {
            $locale = 'en';
        }
        app()->setLocale($locale);
        return $next($request);
    }
}
