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


        //订单报表
        Route::get('orderchart','ChartsController@order');
        //团队报表
        Route::get('teamchart', 'ChartsController@team');
        //提现报表
        Route::get('withdraw', 'ChartsController@withdraw');

        //浏览记录
        Route::resource('history', 'HistoriesController',[
            'only' => ['index','store']
        ]);

    });