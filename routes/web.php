<?php

Route::middleware ('web')->namespace ('Frontend')->group (function () {
    Route::get ('/', 'HomeController@index')->name ('home');
});


Route::middleware ('web')->prefix ('wechat')->namespace ('Wechat')->group (function () {
    Route::get ('mini_program/serve', 'MiniProgramController@serve');
    Route::get ('official_account/serve', 'OfficialAccountController@serve');
});

