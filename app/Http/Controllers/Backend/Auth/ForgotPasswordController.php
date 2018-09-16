<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\User\ForgotPasswordRequest;

/**
 * 找回密码
 * Class AuthController
 * @package App\Http\Controllers\Backend\Auth
 */
class ForgotPasswordController extends Controller
{

    /**
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetEmail(ForgotPasswordRequest $request)
    {

        $validate = $this->validator ($request->all ());

        if ($validate->errors ()->first ()) {

            return json (4001, $validate->errors ()->first ());
        }

        $user = db ('users')->where ('email', $request->get ('email'))->first ();
        if (!$user) {
            return json (4001, '邮箱不存在');
        }

        $broker = $this->getPasswordBroker ();
        $sendingResponse = $broker->sendResetLink ($request->only ('email'));

        if ($sendingResponse !== Password::RESET_LINK_SENT) {
            return json (4001, '邮件发送失败');
        }
        return json (1001, '邮件发送成功');

    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    private function getPasswordBroker()
    {
        return Password::broker ();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'email.required' => '请填写邮箱',
            'email.email' => '邮箱格式错误',
        ];

        return Validator::make ($data, [
            'email' => 'required|string|email|max:255',
        ], $message);
    }
}
