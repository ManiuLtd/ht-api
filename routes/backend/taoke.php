<?php

Route::namespace('Taoke')
    ->prefix('taoke')
    ->group(function (){
        //产品分类
        Route::resource('category','CategoriesController');


        //圈子
        Route::resource('quan', 'QuansController');


        //推广位
        Route::resource('pid','PidsController');

        //订单
        Route::resource('order','OrdersController',[
            'only' => ['index', 'destroy'],
        ]);
    });