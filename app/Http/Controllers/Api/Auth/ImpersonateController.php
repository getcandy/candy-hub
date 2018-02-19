<?php

namespace GetCandy\Http\Controllers\Api\Auth;

use GetCandy\Http\Controllers\Api\BaseController;
use GetCandy\Http\Requests\Api\Auth\ImpersonateRequest;
use Passport;

class ImpersonateController extends BaseController
{
    public function process(ImpersonateRequest $request)
    {
        $token = app('api')->users()->getImpersonationToken($request->customer_id);

        return response()->json([
            'access_token' => $token->accessToken
        ]);
    }
}
