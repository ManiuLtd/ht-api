<?php

Route::namespace('System')
//    ->middleware('jwt.auth')
    ->prefix('system')
    ->group(function () {

        //通知
        Route::resource('notification', 'NotificationsController',[
            'except' => ['show', 'edit', 'update'],
        ]);
        //留言
        Route::resource('feedback', 'FeedbacksController',[
            'only' => ['index'],
        ]);
        //短信记录
        Route::resource('sms', 'SmsController',[
            'only' => ['index'],
        ]);
    });
