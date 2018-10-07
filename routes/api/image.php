<?php
    //店招列表
    Route::namespace('Image')
        ->group(function () {
            //图标列表
            Route::post('image','BannersController@index');
        });
