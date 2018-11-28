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
        Route::post('appPayment', 'Wechat\PaymentController@app');
        Route::get('success', 'Wechat\PaymentController@success');
    });
        //h5支付回调
        Route::any('payment/wechatNotify', 'OfficialAccount\Wechat\PaymentController@notify');


