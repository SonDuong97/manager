<?php

namespace App\Http\Controllers\Staff;

use App\Http\Resources\Staff\TimesheetResource;
use App\Models\Timesheet;
use App\Models\User;
use App\Http\Controllers\Staff\Controller as StaffController;
use App\Services\Interfaces\DatatableServiceInterface;
use App\Services\Interfaces\TimesheetServiceInterface;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ManagerController extends StaffController
{
    protected $datatableService;
    protected $timesheetService;
    /**
     * ManagerController constructor.
     */
    public function __construct(DatatableServiceInterface $datatableService, TimesheetServiceInterface $timesheetService)
    {
        parent::__construct();
        $this->middleware('pass_if_role_is_manager');
        $this->datatableService = $datatableService;
        $this->timesheetService = $timesheetService;
    }

    public function getTimesheetList()
    {
        $timesheets = Timesheet::whereIn(Timesheet::COL_USER_ID, function ($query) {
            $query->select('id')
                ->from('users')
                ->where(User::COL_MANAGER_ID, Auth::id());
        })->where(Timesheet::COL_APPROVED, Timesheet::NOT_APPROVED);

        return $this->datatableService->timesheets($timesheets);
    }

    public function indexTimesheet()
    {
        return view('manager.approve');
    }

    public function showTimesheet($id)
    {
        $timesheet = new TimesheetResource(Timesheet::where(Timesheet::COL_ID, $id)
            ->first());

        return view('staff.timesheets.show', ['timesheet' => $timesheet]);
    }

    public function approveTimesheet($id)
    {
        $timesheet = Timesheet::find($id);
        $managerId = $timesheet->user->manager->id;

        if ($managerId == Auth::id()) {
            if ($this->timesheetService->approveTimesheet($timesheet)) {
                return 204;
            }
        }

        abort(403, "Forbidden!!!");
    }
}
