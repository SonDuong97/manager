<?php

Route::get('/dashboard', 'UsersController@showDashboard')->name('staff.dashboard');

Route::resource('/timesheets', 'TimesheetsController')->except(['destroy']);
Route::get('/show-timesheet-list', 'TimesheetsController@showTimesheetList');

//Manager
Route::get('/get-staff-list', 'ManagerController@getTimesheetList')->name('manager.get.timesheet');
Route::get('/show-staff-list', 'ManagerController@showStaffList')->name('manager.show.staff');
Route::get('/show-timesheet/{id}', 'ManagerController@showTimesheet')->name('manager.show.timesheet');
Route::get('/approve-timesheet/{id}', 'ManagerController@approveTimesheet')->name('manager.approve');
