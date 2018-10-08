<?php

Route::namespace('Member')->group(function (){


    //提现
    Route::get('withdraw', 'WithdrawsController@show');


    //申请提现
    Route::post('withdraw/submit', 'WithdrawsController@index');
});


