<?php

namespace App\Http\Controllers\Api\Auth;

use Validator;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Controllers\Auth\Passwords\Facade\Password;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     * @throws \Exception
     */
    public function reset(Request $request)
    {
        //验证表单内容
        $validate = Validator::make($request->all(), $this->rules());
        if ($validate->fails()) {
            return json(4001, $validate->getMessageBag()->first());
        }
        $user = User::query()->where([
            'phone' => $request->phone,
        ])->first();

        if (! $user) {
            return json(4001, '手机号不存在');
        }
        if (isset($request->phone) && ! checkSms($request->phone, $request->code)) {
            //手机验证码错误次数超过5次时重新验证
            $errorTime = cache('password:phone:error:'.$request->phone) ?? 0;
            $errorTime = $errorTime + 1;
            Cache::put('password:phone:error:'.$request->phone, $errorTime, now()->addSecond(env('VERIFY_CODE_EXPIRED_TIME')));

            if ($errorTime > 5) {
                Cache::forget('password:phone:error:'.$request->phone);

                return json(4001, '验证码错误次数过多');
            }

            return json(4001, '验证码错误');
        }

        $this->resetPassword($user, $request->password);

        return json(1001, '密码重置成功');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'code' => 'required',
            'phone' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
