<?php


Route::namespace('System')
//    ->middleware('jwt.auth')
    ->group(function () {
        //设置
        Route::resource('setting', 'SettingsController', [
            'only' => ['index', 'edit'],
        ]);
    });
