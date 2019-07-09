<?php

namespace App\Http\Controllers\Staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function showDashboard() {
        return view('staff.dashboard');
    }
}
