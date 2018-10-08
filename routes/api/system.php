<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('System')
    ->prefix('system')
    ->group(function () {
        //通知列表
        Route::get('notification', 'NotificationsController@index');
    });