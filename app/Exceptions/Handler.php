<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //参数格式错误
        if ($exception instanceof \InvalidArgumentException) {
            return json(4001, $exception->getMessage());
        }
        //jwt相关异常
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {

            //判断token是否存在
            if (! auth()->guard()->parser()->setRequest($request)->hasToken()) {
                return json(4002, 'Token not provided', null, 401);
            }

            //判断token是否正常
            try {
                if (! auth()->guard()->parseToken()->authenticate()) {
                    return json(4002, 'User not exists', null, 401);
                }
            } catch (Exception $e) {
                return json(4002, 'Token is error', null, 401);
            }
        }
        //token黑名单
        if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
            //判断token 是否注销或者过期
            if (! \auth()->guard('api')->user()) {
                return json(4002, 'The token has been blacklisted', null, 401);
            }
        }
        //未绑定手机号
        if ($exception instanceof PhoneException) {
            return json(4003, 'Phone is not bind');
        }

        //未绑定邀请人
        if ($exception instanceof InviterException) {
            return json(4004, 'Inviter is not bind');
        }

        return parent::render($request, $exception);
    }
}
