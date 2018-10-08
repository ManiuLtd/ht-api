<?php

    Route::namespace('System')
        ->group(function () {
            //添加留言反馈
            Route::post('feedback','FeedbacksController@index');
            //创建通知列表
            Route::post('notification','NotificationsController@index');
            //获取配置信息
            Route::get('setting','SettingsController@index');
        });
