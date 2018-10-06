<?php

Route::namespace('Taoke')
    ->prefix('taoke')
    ->group(function (){
        //产品分类
        Route::resource('category','CategoriesController');

        //圈子
        Route::resource('quan', 'QuansController');

    });