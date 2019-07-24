<?php
Route::get('/', 'SummaryController@showDashboard')->name('staffs.top');
Route::get('/dashboard', 'SummaryController@showDashboard')->name('staffs.dashboard');
Route::get('/get-log', 'SummaryController@getLog')->name('staffs.getLog');

Route::resource('/timesheets', 'TimesheetController')->except(['destroy']);
Route::get('/get-timesheets', 'TimesheetController@getTimesheets')->name('timesheets.get_all');

//Manager
Route::get('/get-staff-list', 'ManagerController@getTimesheetList')->name('manager.get.timesheet');
Route::get('/index-timesheet', 'ManagerController@indexTimesheet')->name('manager.index.timesheet');
Route::get('/show-timesheet/{id}', 'ManagerController@showTimesheet')->name('manager.show.timesheet');
Route::get('/approve-timesheet/{id}', 'ManagerController@approveTimesheet')->name('manager.approve');
