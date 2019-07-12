<?php

Route::get('/dashboard', 'UsersController@showDashboard')->name('staff.dashboard');

Route::resource('/timesheets', 'TimesheetsController')->except(['destroy']);
Route::get('/show-timesheet-list', 'TimesheetsController@showTimesheetList');

//Manager
Route::get('/get-staff-list', 'ManagerController@getStaffList')->name('manager.get.staff');
