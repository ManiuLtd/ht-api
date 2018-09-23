<?php

Route::namespace('User')
    ->middleware('jwt.auth')
    ->group(function () {

    //用户
        Route::resource('user', 'UsersController', [
        'except' => ['create', 'edit'],
    ]);
    });
