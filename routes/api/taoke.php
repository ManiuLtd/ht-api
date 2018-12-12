<?php
Route::namespace('Taoke')
    ->prefix('taoke')
    ->middleware(['jwt.auth'])
    ->group(function () {

        //优惠卷
        Route::get('coupon', 'CouponsController@index');
        //优惠券详情

        Route::get('coupon/detail', 'CouponsController@detail');
        //猜你喜欢
        Route::get('guess', 'GuessController@index');

        //分类
        Route::get('category', 'CategoriesController@index');
        //超级分类
        Route::get('category/super', 'CategoriesController@super_category');

        //搜索
        Route::get('search', 'SearchController@index');
        Route::get('search/hot', 'SearchController@keywords');

        //快抢商品
        Route::get('kuaiqiang', 'KuaiQiangController@index');
        //好货
        Route::resource('haohuo', 'HaoHuoController', [
            'only' => ['index'],
        ]);

        //订单列表
        Route::get('order', 'OrdersController@index');

        //收藏
        Route::resource('favourite', 'FavouritesController');

        //订单报表
        Route::get('chart/order', 'ChartsController@order');

        //用户收入信息
        Route::get('chart/member', 'ChartsController@member');

        //浏览记录
        Route::resource('history', 'HistoriesController');
        
        //详情分享二维码
        Route::get('qrcode/share', 'QrcodeController@share');

        //邀请二维码
        Route::get('qrcode/invite', 'QrcodeController@invite');

        //专题列表
        Route::get('zhuanti', 'ZhuanTiController@index');

        //精选单品
        Route::get('jingxuan', 'JingXuanController@index');
        Route::get('jingxuan/kouling', 'JingXuanController@kouLing');

<<<<<<< HEAD
        //随机数据
        Route::get('random', 'RandomsController@index');
=======

        //小店分类
        Route::get('dian/category', 'DianCategoryController@index');
        //小店
        Route::resource('dian', 'DianController')->only(['index','show']);


        Route::get('entrance', 'EntrancesController@index');
        Route::get('entrance/category', 'EntranceCategoriesController@index');
>>>>>>> 8cf93ef6d4ffa74c752d36c01ebdf3d1ed6976bb

    });
