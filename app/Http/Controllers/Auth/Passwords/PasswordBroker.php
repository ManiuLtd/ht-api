<?php

namespace App\Http\Controllers\Auth\Passwords;

use App\Events\SendSMS;
use App\Http\Controllers\Auth\Passwords\Facade\Password;
use Closure;
use Illuminate\Auth\Passwords\PasswordBroker as AuthPasswordBroker;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\PasswordBroker as PasswordBrokerContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use UnexpectedValueException;

/**
 * Class PasswordBroker.
 */
class PasswordBroker extends AuthPasswordBroker
{
    /**
     * Send a password reset link to a user.
     *
     * @param  array $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        // First we will check to see if we found a user at the given credentials and
        // if we did not we will redirect back to this current URI with a piece of
        // "flash" data in the session to indicate to the developers the errors.
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        // Once we have the reset token, we are ready to send the message out to this
        // user with a link to reset their password. We will then redirect back to
        // the current URI having nothing set in the session to indicate errors.
        $token = rand(100000, 999999);

        $user->sendPasswordResetNotification($token);

        Cache::put('password:email:'.$credentials['email'], $token, now()->addSecond(env('VERIFY_CODE_EXPIRED_TIME')));

        return static::RESET_LINK_SENT;
    }

    /**
     * @param array $credentials
     * @param Closure|null $callback
     * @return string
     * @throws \Exception
     */
    public function sendResetCode(array $credentials, Closure $callback = null)
    {
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return PasswordBrokerContract::INVALID_USER;
        }
        //我是将手机验证码发送后保持在Redis中，验证的时候也是去redis取
        $phone = $credentials['phone'];

        $token = rand(100000, 999999);

        try {
            event(new SendSMS($phone, env('JUHE_SMS_VERIFY_TEMPLATE_ID'), $token));
            Cache::put('password:phone:'.$phone, $token, now()->addSecond(env('VERIFY_CODE_EXPIRED_TIME')));

            return PasswordBrokerContract::RESET_LINK_SENT;
        } catch (\Exception $e) {
            return Password::SEND_FAIL;
        }
    }

    /**
     * 通过邮箱重置密码
     * @param array $credentials
     * @param Closure $callback
     * @return \Illuminate\Contracts\Auth\Authenticatable|CanResetPasswordContract|null|string
     * @throws \Exception
     */
    public function resetByEmail(array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        if (! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        $pass = $credentials['password'];

        call_user_func($callback, $user, $pass);
        //如果是手机号重置密码的话新密码保存后需要删除缓存的验证码
        Cache::forget('password:email:'.$credentials['email']);

        return PasswordBrokerContract::PASSWORD_RESET;
    }

    /**
     * 通过手机重置密码
     * @param array $credentials
     * @param Closure $callback
     * @return \Illuminate\Contracts\Auth\Authenticatable|CanResetPasswordContract|null|string
     * @throws \Exception
     */
    public function resetByPhone(array $credentials, Closure $callback)
    {
        $user = $this->validateReset($credentials);

        if (! $user instanceof CanResetPasswordContract) {
            return $user;
        }

        $pass = $credentials['password'];

        call_user_func($callback, $user, $pass);
        //如果是手机号重置密码的话新密码保存后需要删除缓存的验证码
        Cache::forget('password:phone:'.$credentials['phone']);

        return PasswordBrokerContract::PASSWORD_RESET;
    }

    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|CanResetPasswordContract|null|string
     * @throws \Exception
     */
    protected function validateReset(array $credentials)
    {
        if (is_null($user = $this->getUser($credentials))) {
            return PasswordBrokerContract::INVALID_USER;
        }

        if (! $this->validateNewPassword($credentials)) {
            return PasswordBrokerContract::INVALID_PASSWORD;
        }

        //验证码是否存在
        if (isset($credentials['token'])) {

            //邮件验证码
            if (isset($credentials['email']) && (cache('password:email:'.$credentials['email']) != $credentials['token'])) {
                //邮箱验证码错误次数超过5次时重新验证
                $errorTime = cache('password:email:error:'.$credentials['email']) ?? 0;
                $errorTime = $errorTime + 1;

                Cache::put('password:email:error:'.$credentials['email'], $errorTime, now()->addSecond(env('VERIFY_CODE_EXPIRED_TIME')));

                if ($errorTime > 5) {
                    Cache::forget('password:email:error:'.$credentials['email']);

                    return Password::MAX_ERROR;
                }

                return PasswordBrokerContract::INVALID_TOKEN;
            }

            //手机验证码
            if (isset($credentials['phone']) && (cache('password:phone:'.$credentials['phone']) != $credentials['token'])) {
                //手机验证码错误次数超过5次时重新验证
                $errorTime = cache('password:phone:error:'.$credentials['phone']) ?? 0;
                $errorTime = $errorTime + 1;
                Cache::put('password:phone:error:'.$credentials['phone'], $errorTime, now()->addSecond(env('VERIFY_CODE_EXPIRED_TIME')));

                if ($errorTime > 5) {
                    Cache::forget('password:phone:error:'.$credentials['phone']);

                    return Password::MAX_ERROR;
                }

                return PasswordBrokerContract::INVALID_TOKEN;
            }
        } elseif (! $this->tokens->exists($user, $credentials['token'])) {
            return PasswordBrokerContract::INVALID_TOKEN;
        }

        return $user;
    }

    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|CanResetPasswordContract|null
     */
    public function getUser(array $credentials)
    {
        $credentials = Arr::except($credentials, ['token']);

        $user = $this->users->retrieveByCredentials($credentials);

        if ($user && ! $user instanceof CanResetPasswordContract) {
            throw new UnexpectedValueException('User must implement CanResetPassword interface.');
        }

        return $user;
    }
}
