<?php
Route::namespace ('Shop')->prefix ('shop')->group (function () {

    //商品分类
    Route::resource ('goods/category', 'ShopGoodsCategoriesController', [
        'except' => ['create', 'edit']
    ]);

    //商品标签
    Route::resource ('goods/tag', 'ShopGoodsTagsController', [
        'except' => ['create', 'edit']
    ]);


    //商品标签
    Route::resource ('goods/comment', 'ShopGoodsCommentsController', [
        'except' => ['create', 'edit', 'store']
    ]);

    //商品
    Route::resource ('goods', 'ShopGoodsController', [
        'except' => ['create', 'edit']
    ]);


    //维权订单
    Route::resource ('order/refund', 'ShopOrderGoodsRefundsController', [
        'except' => ['create', 'edit', 'store']
    ]);

    //订单
    Route::resource ('order', 'ShopOrdersController', [
        'except' => ['create', 'edit', 'store']
    ]);


    //优惠券分类
    Route::resource ('coupon/category', 'ShopCouponCategoriesController', [
        'except' => ['create', 'edit']
    ]);

    //会员日志
    Route::resource ('coupon/log', 'ShopCouponLogsController', [
        'except' => ['create', 'update', 'edit', 'destroy', 'store']
    ]);

    //优惠券
    Route::resource ('coupon', 'ShopCouponsController', [
        'except' => ['create', 'edit']
    ]);
});
