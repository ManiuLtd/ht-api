<?php
<<<<<<< HEAD

    Route::namespace('System')
        ->group(function () {
            //添加留言反馈
            Route::post('feedback','FeedbacksController@index');
            //创建通知列表
            Route::post('notification','NotificationsController@index');
            //获取配置信息
            Route::get('setting','SettingsController@index');
        });
=======
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
>>>>>>> 7a260ca8f19a0cddc04882d87aeb7be10ea0244b
