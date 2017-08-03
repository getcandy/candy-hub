<?php

namespace GetCandy\Http\Middleware;

use Closure;
use GetCandy\Api\Traits\Fractal;
use Locale;

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
        $defaultLanguage = app('api')->languages()->getDefaultRecord()->lang;
        if (!$locale) {
            $locale = $defaultLanguage;
        } else {
            // Try and find a language code...
            $requestedLocale = app('api')->languages()->getEnabledByCode(Locale::getPrimaryLanguage($locale));
            $locale = $requestedLocale->lang;
            if (!$requestedLocale) {
                $locale = $defaultLanguage;
            }
        }
        app()->setLocale($locale);
        return $next($request);
    }
}
