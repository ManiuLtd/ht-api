<?php

Route::namespace('Member')
    ->prefix('member')
    ->group(function () {
        //会员信息
        Route::get('friend', 'MembersController@index');
        //好友列表
        Route::get('friend_list', 'MembersController@friends');
    });
