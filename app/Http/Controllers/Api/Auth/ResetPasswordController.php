<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Auth\Passwords\Facade\Password;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Validator;

class ResetPasswordController extends Controller
{

    use ResetsPasswords;

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        //验证表单内容
        $validate = Validator::make ($request->all (), $this->rules ());
        if ($validate->fails ()) {
            return json (4001, $validate->getMessageBag ());
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker ()->resetByEmail (
            $this->credentials ($request), function ($user, $password) {
            $this->resetPassword ($user, $password);
        }
        );

        if ($response !== Password::PASSWORD_RESET) {
            if ($response == Password::MAX_ERROR) {
                return json (4001, '验证码错误次数过多，请重新接受验证码');
            }
            return json (4001, '验证码错误');
        }

        return json (1001, "密码重置成功");
    }


    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker ();
    }
}