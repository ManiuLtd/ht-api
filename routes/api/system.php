<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('System')
    ->prefix('system')
    ->middleware(['jwt.auth'])
    ->group(function () {
        //添加留言反馈
        Route::resource('feedback', 'FeedbacksController', [
            'only' => ['index', 'store'],
        ]);
        //通知列表
        Route::get('notification', 'NotificationsController@index');

    });

Route::namespace('System')
    ->prefix('system')
    ->group(function () {
        //获取配置信息
        Route::get('setting', 'SettingsController@index');
    });
