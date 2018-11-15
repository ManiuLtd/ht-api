<?php
/**
 * Created by PhpStorm.
 * User: hongtang
 * Date: 2018/11/15
 * Time: 14:47
 */
Route::namespace('Sms')
    ->group(function () {
        Route::get('sendSms','SmsController@index');
    });
