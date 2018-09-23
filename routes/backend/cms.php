<?php

Route::namespace('Cms')
    ->middleware('jwt.auth')
    ->prefix('cms')
    ->group(function () {
    });
