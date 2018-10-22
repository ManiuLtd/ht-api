<?php

Route::namespace('Taoke')
    ->prefix('taoke')
    ->group(function () {
        //订单列表
        Route::get('order', 'OrdersController@index');
        //提交订单
        Route::post('order/submit', 'OrdersController@submit');

        //收藏
        Route::resource('favourite', 'FavouritesController');

        //订单报表
        Route::get('chart/order', 'ChartsController@order');

        //提现报表
        Route::get('chart/withdraw', 'ChartsController@withdraw');

        //浏览记录
        Route::resource('history', 'HistoriesController', [
            'only' => ['index', 'store', 'destory'],
        ]);

        //分类
        Route::get('category', 'CategoriesController@index');


        //优惠卷
        Route::resource('coupon', 'CouponsController', [
            'only' => ['index', 'show'],
        ]);

        //搜索

        Route::get('search','SearchController@index');
        Route::get('search/hot','SearchController@keywords');



        Route::get('search', 'SearchController@index');
        Route::get('search/hot', 'SearchController@keywords');

        //详情分享二维码
        Route::get('qrcode/share', 'QrcodeController@share');
        //邀请二维码
        Route::get('qrcode/invite', 'QrcodeController@invite');

        //专题列表
        Route::get('zhuanti','ZhuanTiController@index');

        //快抢商品
        Route::get('kuaiqiang','KuaiQiangController@index');


        //好货
        Route::resource('haohuo', 'HaoHuoController', [
            'only' => ['index'],
        ]);
    });

