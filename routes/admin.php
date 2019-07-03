<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

//Login
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
Route::post('/login', 'Auth\LoginController@login')->name('admin.login.post');

//Logout
Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout');

Route::get('/', 'TopController@index')->name('admin.top');