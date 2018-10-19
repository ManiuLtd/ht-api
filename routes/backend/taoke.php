<?php

Route::namespace('Taoke')
    ->prefix('taoke')
    ->group(function () {
        //产品分类
        Route::resource('category', 'CategoriesController');



        //推广位
        Route::resource('pid', 'PidsController', [
            'except' => 'show',
        ]);

        //订单
        Route::resource('order', 'OrdersController', [
            'only' => ['index', 'show'],
        ]);

        //优惠卷
        Route::resource('coupon', 'CouponsController');
        //专题
        Route::resource('zhuanti', 'ZhuanTiController');
        //好货
        Route::resource('haohuo', 'HaohuoController',[
            'except'=> ['create','edit']
        ]);

        //精选单品
        Route::resource('jinxuandp', 'JingXuanController',[
            'except'=> ['create','edit']
        ]);

        //快抢商品
        Route::resource('kuaiqiang', 'KuaiQiangController');
    });
