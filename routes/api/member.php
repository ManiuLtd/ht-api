<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('Member')
    ->prefix('member')
    ->group(function () {
        //积分余额日志
        Route::get('creditLog', 'CreditLogsController@index');
    });