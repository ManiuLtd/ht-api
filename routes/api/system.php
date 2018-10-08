<?php

    Route::namespace('System')
        ->group(function () {
            //添加留言反馈
            Route::post('feedback','FeedbacksController@index');
            //创建通知列表
            Route::post('notification','NotificationController@index');
        });
