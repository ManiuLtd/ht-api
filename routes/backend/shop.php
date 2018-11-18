<?php

Route::namespace('Shop')
    ->middleware('jwt.auth')
    ->prefix('shop')
    ->group(function () {

    //商品分类
        Route::resource('goods/category', 'CategoriesController', [
        'except' => ['create', 'edit', 'show'],
    ]);

        //商品标签
        Route::resource('goods/tag', 'GoodsTagsController', [
        'except' => ['create', 'edit'],
    ]);

        //商品评论
        Route::resource('goods/comment', 'GoodsCommentsController', [
        'except' => ['create', 'edit', 'store'],
    ]);

        //商品
        Route::resource('goods', 'GoodsController', [
        'except' => ['create', 'edit'],
    ]);

        //维权订单
        Route::resource('order/refund', 'RefundOrdersController', [
        'except' => ['create', 'edit', 'store'],
    ]);

        //订单
        Route::resource('order', 'OrdersController', [
        'except' => ['create', 'edit', 'store'],
    ]);

        //优惠券分类
        Route::resource('coupon/category', 'CouponCategoriesController', [
        'except' => ['create', 'edit', 'show'],
    ]);

        //会员日志
        Route::resource('coupon/log', 'CouponLogsController', [
        'except' => ['create', 'update', 'edit', 'destroy', 'store'],
    ]);

        //优惠券
        Route::resource('coupon', 'CouponsController', [
        'except' => ['create', 'edit'],
    ]);
    });
