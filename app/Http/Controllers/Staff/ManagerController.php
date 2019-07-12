<?php

namespace App\Http\Controllers\Staff;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManagerController extends Controller
{

    /**
     * ManagerController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.manager.role']);
    }

    public function getStaffList()
    {
        $staffList = User::where(User::COL_MANAGER_ID, Auth::id())->get();

        return $staffList;
    }

    public function showStaffList() {

    }
}
