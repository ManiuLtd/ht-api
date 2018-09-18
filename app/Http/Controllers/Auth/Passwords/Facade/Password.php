<?php

namespace App\Http\Controllers\Auth\Passwords\Facade;
use Illuminate\Support\Facades\Password as AuthPassword;


/**
 * Class Password
 * @package App\Http\Controllers\Backend\Auth\Passwords\Facade
 */
class Password extends AuthPassword
{
    /**
     * 验证码错误次数过多
     */
    const MAX_ERROR = 'passwords.max_error';

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'auth.password';
    }
}