<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Auth\Passwords\Facade\Password;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Validator;

/**
 * 找回密码
 * Class AuthController
 * @package App\Http\Controllers\Backend\Auth
 */
class ForgotPasswordController extends Controller
{

    use SendsPasswordResetEmails;

    /**
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetEmail(ForgotPasswordRequest $request)
    {

        //验证表单内容
        $validate = Validator::make ($request->all (), $this->rules ());
        if ($validate->fails ()) {
            return json (4001, $validate->getMessageBag ());
        }

        $this->validateEmail ($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $response = $this->broker ()->sendResetLink (
            $request->only ('email')
        );

        //验证邮件状态
        switch ($response) {
            case Password::INVALID_USER:
                return json (4001, '邮箱不存在');
                break;
            case Password::RESET_LINK_SENT:
                return json (1001, '邮件发送成功');
                break;
            default:
                return json (4001, '邮件发送失败');
                break;
        }
    }


    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
