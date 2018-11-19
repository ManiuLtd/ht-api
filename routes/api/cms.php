<?php

Route::namespace('Cms')
    ->prefix('cms')
    ->middleware(['jwt.auth'])
    ->group(function () {
        //会员信息
        Route::resource('article', 'ArticlesController');
        Route::get('category', 'CategoriesController@index');
        Route::get('project', 'ProjectsController@index');
    });
