<?php
/*
 * Created by PhpStorm.
 * User: niugengyun
 * Date: 2018/9/19
 * Time: 22:25
 */
Route::namespace('Taoke')
    ->prefix('taoke')
    ->group(function () {
        //订单
        Route::get('order', 'OrdersController@index');
        //收藏
        Route::resource('favourite', 'FavouritesController');
    });