<?php

Route::namespace('Cms')
    ->prefix('cms')
    ->middleware(['jwt.auth'])
    ->group(function () {
        //会员信息
        Route::get('article', 'ArticlesController@index');
        Route::get('article/show', 'ArticlesController@show');
        Route::get('category', 'CategoriesController@index');
        Route::get('project', 'ProjectsController@index');
    });
