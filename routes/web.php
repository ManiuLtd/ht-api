<?php

Route::middleware('web')->namespace('Frontend')->group(function () {
    Route::get('/', 'HomeController@index')->name('home');
    //测试

    Route::get('admin', function (){
        return view('admin');
    });


});

Route::middleware('web')->prefix('wechat')->namespace('Wechat')->group(function () {
    Route::any('mini_program/serve', 'MiniProgramController@serve');
    Route::any('official_account/serve', 'OfficialAccountController@serve');
});
