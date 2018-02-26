<?php

namespace GetCandy\Http\Controllers\Api\Auth;

use GetCandy\Http\Controllers\Api\BaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use GetCandy\Http\Requests\Api\Auth\ResetAccountPasswordRequest;
use Illuminate\Support\Facades\Password;

class AccountController extends BaseController
{
   public function resetPassword(ResetAccountPasswordRequest $request)
   {
        $result = app('api')->users()->resetPassword($request->current_password, $request->password, $request->user());

        if (!$result) {
            return $this->errorForbidden();
        }

        return $this->respondWithSuccess('Password changed');
   }
}
