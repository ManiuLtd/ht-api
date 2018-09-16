<?php
Route::namespace ('User')->group (function () {

    //用户
    Route::resource ('user', 'UsersController', [
        'except' => ['create', 'edit']
    ]);
});