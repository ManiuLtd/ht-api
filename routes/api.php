<?php
/**
 *  每个模块按照独立模块区分 这样协同开发时 修改路由的话 代码不会冲突
 */
Route::middleware('guard:user', 'api')->namespace('Backend')->prefix('admin')->group(function () {
    include_route_files(__DIR__.'/backend/');
});

Route::middleware ('guard:user', 'api')->namespace ('Api')->group (function () {

    include_route_files (__DIR__ . '/api/');
});
