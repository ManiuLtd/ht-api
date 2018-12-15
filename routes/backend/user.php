<?php

Route::namespace('User')
    ->middleware('jwt.auth')
    ->group(function () {
        Route::get('user/current', function () {
            try {
                return json(1001, '用户信息获取成功', auth()->user());
            } catch (\Exception $e) {
                return json(5001, '用户信息获取失败', $e->getMessage());
            }
        });

        //会员日志
        Route::resource('user/credit-log', 'CreditLogsController', [
            'only' => ['index'],
        ]);

        //收货地址
        Route::resource('user/address', 'AddressesController', [
            'only' => ['index'],
        ]);

        //用户收藏
        Route::resource('user/favourite', 'FavouritesController', [
            'only' => ['index'],
        ]);

        //会员日志
        Route::resource('user/history', 'HistoriesController', [
            'only' => ['index'],
        ]);

        //会员等级
        Route::resource('user/level', 'LevelsController');

        //提现记录
        Route::resource('user/withdraw', 'WithdrawsController', [
            'except' => ['create', 'edit', 'store'],
        ]);
        //提现审核
        Route::get('user/withdraw/mark', 'WithdrawsController@mark');

        //充值记录
        Route::get('user/recharge', 'RechargesController@index');
        //付款记录
        Route::get('user/payment', 'PaymentsController@index');

        //团队
        Route::resource('user/group', 'GroupsController');

        //会员
        Route::resource('user', 'UsersController', [
            'except' => ['create', 'edit', 'store'],
        ]);

        //分销等级
        Route::resource('level', 'LevelsController');
    });
