<?php

//用户登录
Route::namespace('Auth')
    ->prefix('auth')
    ->group(function () {
        //登录
        Route::post('login', 'LoginController@login');

        //退出
        Route::get('logout', 'LogoutController@logout')->middleware('jwt.auth');

    });
