<?php

Route::namespace('Cms')
//    ->middleware('jwt.auth')
    ->prefix('cms')
    ->group(function () {
        Route::resource('categories', 'CategoriesController');
        Route::resource('projects', 'ProjectsController');
    });
