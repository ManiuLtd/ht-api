<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:24
 */

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
        //重置密码
        Route::post('password/reset', 'ResetPasswordController@reset');
    });


