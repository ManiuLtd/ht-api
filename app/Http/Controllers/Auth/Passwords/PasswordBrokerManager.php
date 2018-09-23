<?php

namespace App\Http\Controllers\Auth\Passwords;

use Closure;
use InvalidArgumentException;
use Illuminate\Auth\Passwords\PasswordBrokerManager as AuthPasswordBrokerManager;

/**
 * Class PasswordBrokerManager.
 */
class PasswordBrokerManager extends AuthPasswordBrokerManager
{
    /**
     * @param string $name
     * @return PasswordBroker|\Illuminate\Contracts\Auth\PasswordBroker
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Password resetter [{$name}] is not defined.");
        }

        //这里实例化我们自定义的PasswordBroker来完成密码重置逻辑
        return new PasswordBroker($this->createTokenRepository($config),
            $this->app['auth']->createUserProvider($config['provider'])
        );
    }

    /**
     * Send a password reset link to a user.
     *
     * @param  array $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        // TODO: Implement sendResetLink() method.
    }

    /**
     * Reset the password for the given token.
     *
     * @param  array $credentials
     * @param  \Closure $callback
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback)
    {
        // TODO: Implement reset() method.
    }

    /**
     * Set a custom password validator.
     *
     * @param  \Closure $callback
     * @return void
     */
    public function validator(Closure $callback)
    {
        // TODO: Implement validator() method.
    }

    /**
     * Determine if the passwords match for the request.
     *
     * @param  array $credentials
     * @return bool
     */
    public function validateNewPassword(array $credentials)
    {
        // TODO: Implement validateNewPassword() method.
    }
}
