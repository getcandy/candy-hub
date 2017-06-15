<?php

namespace GetCandy\Http\Transformers\Fractal;

use League\Fractal\TransformerAbstract;

abstract class BaseTransformer extends TransformerAbstract
{
    /**
     * Returns the correct translation for a name array
     * @param  mixed $name
     * @return string
     */
    protected function getLocalisedName($name)
    {
        if (! is_array($name)) {
            $name = json_decode($name, true);
        }
        $locale = app()->getLocale();
        $requestLang = strtolower(app('request')->language);
        if ($requestLang) {
            if ($requestLang != 'all') {
                $locale = $requestLang;
            } else {
                return $name;
            }
        }
        if (!empty($name[$locale])) {
            $name = $name[$locale];
        } else {
            $name = array_shift($name);
        }
        return $name;
    }
}
