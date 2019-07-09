<?php

Route::get('/dashboard', 'UsersController@showDashboard')->name('staff.dashboard');

Route::resource('/timesheets', 'TimesheetsController');
