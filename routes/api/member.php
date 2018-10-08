<?php

/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('Member')
    ->prefix('member')
    ->group(function () {
        //会员信息
        Route::get('friend', 'MembersController@index');
        //好友列表
        Route::get('friend_list', 'MembersController@friends');
        //积分余额日志列表
        Route::get('creditLog', 'CreditLogsController@index');
    });

