<?php

//设置
Route::resource ('setting', 'SettingsController', [
    'except' => ['create', 'edit', 'store', 'destroy','show']
]);