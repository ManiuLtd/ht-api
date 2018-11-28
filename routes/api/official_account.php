<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('OfficialAccount')
    ->prefix('payment')
    ->middleware(['jwt.auth'])
    ->group(function () {
        //支付
        Route::get('app', 'Wechat\PaymentController@app');
    });

Route::namespace('OfficialAccount')
    ->prefix('payment')
    ->group(function () {
        //支付
        Route::any('wechatNotify', 'Wechat\PaymentController@notify');
    });
        //h5支付回调


