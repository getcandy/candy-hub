<?php

namespace GetCandy\Http\Transformers\Fractal\Payments;

use GetCandy\Http\Transformers\Fractal\BaseTransformer;
use GetCandy\Api\Payments\Providers\AbstractProvider;

class ProviderTransformer extends BaseTransformer
{
    public function transform(AbstractProvider $provider)
    {
        $data = [
            'name' => $provider->getName()
        ];

        if (method_exists($provider, 'getClientToken')) {
            $data['client_token'] = $provider->getClientToken();
        }
        return $data;
    }
}
