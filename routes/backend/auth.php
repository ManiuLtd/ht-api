<?php

//用户登录
Route::namespace('Auth')
    ->prefix('auth')
    ->group(function () {
        //登录
        Route::post('login', 'LoginController@login');
        //退出
        Route::get('logout', 'LogoutController@logout')->middleware('jwt.auth');
        //注册
        Route::post('register', 'RegisterController@register');
        //发送邮件
        Route::get('password/email', 'ForgotPasswordController@sendResetEmail')->name('password.reset');
        //重置密码
        Route::get('password/reset', 'ResetPasswordController@reset');
    });
