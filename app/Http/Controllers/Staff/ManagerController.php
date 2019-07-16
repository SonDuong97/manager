<?php

namespace App\Http\Controllers\Staff;

use App\Http\Resources\Staff\TimesheetResource;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ManagerController extends Controller
{

    /**
     * ManagerController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.manager.role']);
    }

    public function getTimesheetList()
    {
        $timesheets = Timesheet::whereIn(Timesheet::USER_ID_COL, function ($query) {
            $query->select('id')
                ->from('users')
                ->where(User::COL_MANAGER_ID, Auth::id());
        })->where(Timesheet::COL_APPROVED, Timesheet::NOT_APPROVED);
        return DataTables::of($timesheets)
            ->addColumn('username', function ($timesheet) {
                if ($timesheet->user) {
                    return $timesheet->user->username;
                } else {
                    return 'N/A';
                }
            })
            ->make(true);
    }

    public function showStaffList()
    {
        return view('manager.approve');
    }

    public function showTimesheet($id)
    {
        $timesheet = new TimesheetResource(Timesheet::where(Timesheet::ID_COL, $id)
            ->first());

        return view('staff.timesheets.show', ['timesheet' => $timesheet]);
    }

    public function approveTimesheet($id)
    {
        $timesheet = Timesheet::where(Timesheet::ID_COL, $id)
            ->update([Timesheet::COL_APPROVED => Timesheet::APPROVED]);
        return $timesheet;
    }
}
