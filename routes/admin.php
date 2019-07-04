<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'TopController@index')->name('admin.top');

Route::resource('users', 'UsersController');