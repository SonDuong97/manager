<?php

namespace App\Http\Controllers\Staff;

use App\Models\SummaryLog;
use App\Http\Controllers\Staff\Controller as StaffController;
use Illuminate\Support\Facades\Auth;

class SummaryController extends StaffController
{
    public function showDashboard() {
        return view('staff.dashboard');
    }

    public function getLog()
    {
        $logs = SummaryLog::where(SummaryLog::COL_USER_ID, Auth::id())->get();

        return response()->json($logs);
    }
}
