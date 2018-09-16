<?php

namespace App\Http\Controllers\Backend\Auth;

use Tymon\JWTAuth\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\User\ResetPasswordRequest;

class ResetPasswordController extends Controller
{

    /**
     * @param ResetPasswordRequest $request
     * @param JWTAuth $JWTAuth
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request, JWTAuth $JWTAuth)
    {
        $response = $this->broker ()->reset (
            $this->credentials ($request), function ($user, $password) {
            $this->reset ($user, $password);
        }
        );
        if ($response !== Password::PASSWORD_RESET) {
            return json (4001, '密码重置令牌已失效');
        }

        $user = db ('users')->where ('email', $request->get ('email'))->first ();

        return json (1001, $JWTAuth->fromUser ($user));
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

    /**
     * Get the password reset credentials from the request.
     *
     * @param  ResetPasswordRequest $request
     * @return array
     */
    protected function credentials(ResetPasswordRequest $request)
    {
        return $request->only (
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string $password
     * @return void
     */
    protected function reset($user, $password)
    {
        $user->password = $password;
        $user->save ();
    }
}