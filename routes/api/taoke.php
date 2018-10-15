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

        //圈子
        Route::get('quan', 'QuansController@index');

        //优惠卷
        Route::resource('coupon', 'CouponsController', [
            'only' => ['index', 'show'],
        ]);

        //搜索
<<<<<<< HEAD
        Route::get('search','SearchController@index');
        Route::get('search/hot','SearchController@keywords');

        //生成邀请海报
        Route::get('inviter','QrcodeController@inviter');

    });
=======
        Route::get('search', 'SearchController@index');
        Route::get('search/hot', 'SearchController@keywords');

        //详情分享二维码
        Route::get('qrcode/share', 'QrcodeController@share');
        //邀请二维码
        Route::get('qrcode/invite', 'QrcodeController@invite');
    });
>>>>>>> 72b05efd33ce20b16a68b62a40bf64f96b2148f6
