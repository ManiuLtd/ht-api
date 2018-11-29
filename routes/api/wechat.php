<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('Wechat')
    ->prefix('wechat')
    ->middleware(['jwt.auth'])
    ->group(function () {
        //支付
        Route::get('payment/app', 'PaymentController@app');
    });

Route::namespace('Wechat')
    ->prefix('wechat')
    ->group(function () {
        //支付
        Route::any('payment/notify', 'PaymentController@notify');
    });
        //h5支付回调


