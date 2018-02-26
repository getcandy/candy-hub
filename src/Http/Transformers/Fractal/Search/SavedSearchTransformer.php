<?php

namespace GetCandy\Http\Transformers\Fractal\Search;

use GetCandy\Api\Routes\Models\Route;
use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use Illuminate\Database\Eloquent\Model;

class SavedSearchTransformer extends BaseTransformer
{
    public function transform(Model $model)
    {
        return [
            'id' => $model->encodedId(),
            'name' => $model->name,
            'payload' => $model->payload,
        ];
    }
}
