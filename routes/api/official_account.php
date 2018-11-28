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
        Route::any('appPayment', 'Wechat\PaymentController@app');
    });
        //h5支付回调
        Route::any('payment/wechatNotify', 'OfficialAccount\Wechat\PaymentController@notify');


