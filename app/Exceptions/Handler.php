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
        parent::report ($exception);
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
            return json (4001, $exception->getMessage ());
        }
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {

            //判断token是否存在
            if (!auth ()->guard ()->parser ()->setRequest ($request)->hasToken ()) {
                return json (4001, 'Token not provided');
            }

            //判断token是否正常
            try {
                if (!auth ()->guard ()->parseToken ()->authenticate ()) {
                    return json (4001, 'User not found');
                }
            } catch (Exception $e) {
                return json (4001, 'Token is error');
            }
        }

        return parent::render ($request, $exception);
    }
}
