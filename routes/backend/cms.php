<?php

Route::namespace('Cms')
    ->middleware('jwt.auth')
    ->prefix('cms')
    ->group(function () {
        Route::resource('article', 'ArticlesController');
        Route::resource('category', 'CategoriesController');
        Route::resource('project', 'ProjectsController');
    });
