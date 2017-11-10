<?php

namespace GetCandy\Http\Middleware;

use Closure;
use GetCandy\Api\Traits\Fractal;
use Locale;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

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
        if (\Request::ip() != '84.45.128.100') {
            throw new MaintenanceModeException(666, 666, 'No no no');
        }
        $locale = $request->header('accept-language');
        $defaultLanguage = app('api')->languages()->getDefaultRecord()->lang;
        if (!$locale) {
            $locale = $defaultLanguage;
        } else {
            $languages = explode(',', Locale::getPrimaryLanguage($locale));
            $requestedLocale = app('api')->languages()->getEnabledByLang($languages);
            if (!$requestedLocale) {
                $locale = $defaultLanguage;
            } else {
                $locale = $requestedLocale->lang;
            }
        }
        app()->setLocale($locale);
        return $next($request);
    }
}
