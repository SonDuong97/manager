<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'UserController@index')->name('admin.top');
Route::resource('users', 'UserController')->except('show');
Route::get('show-list-user', 'UserController@getUsers')->name('users.get_all');

// Timesheet settings
 Route::get('/settings/timesheets', 'SettingTimesheetController@edit')->name('settings.timesheets.edit');
 Route::post('/settings/timesheets', 'SettingTimesheetController@update')->name('settings.timesheets.update');
