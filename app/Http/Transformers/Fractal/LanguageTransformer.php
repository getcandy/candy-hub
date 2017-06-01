<?php

namespace GetCandy\Http\Transformers\Fractal;

use GetCandy\Api\Languages\Models\Language;
use League\Fractal\TransformerAbstract;

class LanguageTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    public function transform(Language $language)
    {
        return [
            'id' => $language->encodedId(),
            'name' => $language->name,
            'code' => $language->code,
            'current' => (bool) ($language->code == app()->getLocale())
        ];
    }
}
