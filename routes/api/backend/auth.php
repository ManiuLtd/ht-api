<?php

//用户登录
Route::namespace ('Auth')
    ->middleware (['guard:user'])
    ->prefix ('auth')
    ->group (function () {

        //登录
        Route::post ('login', 'LoginController@login');
        //退出
        Route::get ('logout', 'LogoutController@logout');
        //注册
        Route::post ('signup', 'SignUpController@signUp');
        //发送邮件
        Route::get ('send_email', 'ForgotPasswordController@sendResetEmail')->name ('password.reset');
        //重置密码
        Route::get ('reset_password', 'ResetPasswordController@reset');

    });
