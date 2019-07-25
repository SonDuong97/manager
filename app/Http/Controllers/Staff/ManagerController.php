<?php

namespace App\Http\Controllers\Staff;

use App\Models\Timesheet;
use App\Http\Controllers\Staff\Controller as StaffController;
use App\Services\Interfaces\DatatableServiceInterface;
use App\Services\Interfaces\TimesheetServiceInterface;
use Illuminate\Support\Facades\Auth;

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
        $timesheets = $this->timesheetService->getTimesheetsNotApproved(Auth::id());

        return $this->datatableService->timesheets($timesheets);
    }

    public function indexTimesheet()
    {
        return view('manager.approve');
    }

    public function showTimesheet($id)
    {
        $timesheet = $this->timesheetService->getTimesheetByID($id)->first();

        return view('staff.timesheets.show', compact('timesheet'));
    }

    public function approveTimesheet($id)
    {
        $timesheet = Timesheet::find($id);
        $managerID = $timesheet->user->manager->id;

        if ($managerID == Auth::id()) {
            if ($this->timesheetService->approveTimesheet($timesheet)) {
                return 204;
            }
        }

        abort(403, "Forbidden!!!");
    }
}
