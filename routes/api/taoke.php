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
        Route::post('submit-order', 'OrdersController@submit');
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

        //分类
        Route::get('category', 'CategoriesController@index');
        //圈子
        Route::get('quan', 'QuansController@index');
        //优惠卷
        Route::resource('coupon', 'CouponsController',[
            'only' => ['index','show']
        ]);
        //优惠卷分享
        Route::get('coupon-share','CouponsController@share');

        //淘宝生成领劵地址
        Route::get('track','TrackOrdersController@track');

        //搜索
        Route::get('search','SearchController@index');
        Route::get('search/hot','SearchController@keywords');

    });