<?php

Route::namespace ('Member')->group (function () {


    //会员日志
    Route::resource ('member/credit/log', 'MemberCreditLogsController', [
        'only' => ['index']
    ]);

    //收货地址
    Route::resource ('member/address', 'MemberAddressesController', [
        'only' => [ 'index']
    ]);

    //用户收藏
    Route::resource ('member/favourites', 'MemberFavouritesController', [
        'only' => [ 'index']
    ]);

    //会员日志
    Route::resource ('member/history', 'MemberHistoriesController', [
        'only' => ['index']
    ]);

    //会员等级
    Route::resource ('member/level', 'MemberLevelsController', [
        'except' => ['create', 'edit']
    ]);

    //会员
    Route::resource ('member', 'MembersController', [
        'except' => ['create', 'edit', 'store']
    ]);


    //提现
    Route::resource ('withdraw', 'MemberWithdrawsController', [
        'except' => ['create', 'edit', 'store']
    ]);

    //充值记录
    Route::get ('recharge', 'MemberRechargesController@index');

});