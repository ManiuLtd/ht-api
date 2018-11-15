<?php

/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('User')
    ->prefix('member')
    ->group(function () {

        //会员信息
        Route::get('/', 'UsersController@index');

        //好友列表
        Route::get('friends', 'UsersController@friends');
        Route::get('inviter', 'UsersController@inviter');

        //分销等级列表
        Route::get('commission-level', 'LevelsController@index');

        //积分余额日志列表
        Route::get('credit-log', 'CreditLogsController@index');

        //发起提现
        Route::resource('withdraw', 'WithdrawsController', [
            'only' => ['index', 'store'],
        ]);
    });
