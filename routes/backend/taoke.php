<?php

Route::namespace('Taoke')
    ->prefix('taoke')
    ->middleware('jwt.auth')
    ->group(function () {
        //产品分类
        Route::resource('category', 'CategoriesController');

        //推广位
        Route::resource('pid', 'PidsController', [
            'except' => 'show',
        ]);

        //订单
        Route::resource('order', 'OrdersController');

        //优惠卷
        Route::resource('coupon', 'CouponsController');
        //专题
        Route::resource('zhuanti', 'ZhuanTiController');
        //好货
        Route::resource('haohuo', 'HaoHuoController', [
            'except'=> ['create', 'edit'],
        ]);

        //精选单品
        Route::resource('jingxuan', 'JingXuanController', [
            'except'=> ['create', 'edit'],
        ]);

        //快抢商品
        Route::resource('kuaiqiang', 'KuaiQiangController');

        //代理设置
        Route::resource('setting', 'SettingsController', [
            'only' => ['index', 'update'],
        ]);

        //小店订单
        Route::resource('dian/order', 'DianOrderController');
        //小店分类
        Route::resource('dian/categories', 'DianCategoriesController');
        //小店标签
        Route::resource('dian/tag', 'DianTagController',[
            'except'=> ['create', 'edit'],
        ]);
        //小店
        Route::resource('dian', 'DianController');


        //超级入口
        Route::resource('entrance', 'EntrancesController');
        //超级入口分类

        Route::resource('entrance/category', 'EntranceCategoriesController');

        //用户报表
        Route::get('chart/user', 'ChartsController@user');

        //订单报表
        Route::get('chart/order', 'ChartsController@order');

    });
