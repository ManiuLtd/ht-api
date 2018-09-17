<?php

Route::namespace ('Image')->prefix ('image')->group (function () {

    //店招
    Route::resource ('banner', 'BannersController', [
        'except' => ['create', 'edit', 'show']
    ]);

    Route::post('file/upload','FileController@upload');

    Route::get('list','ImagesController@index');

});

