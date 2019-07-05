<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'TopController@index')->name('admin.top');

Route::resource('users', 'UsersController')->except('show');

// Timesheet settings
 Route::get('/settings/timesheets', 'TimesheetsController@edit')->name('settings.timesheets.edit');
 Route::post('/settings/timesheets', 'TimesheetsController@update')->name('settings.timesheets.update');
