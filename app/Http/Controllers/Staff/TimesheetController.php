<?php

namespace App\Http\Controllers\Staff;

use App\Http\Requests\Timesheet\CreateTimesheetRequest;
use App\Http\Resources\Staff\TimesheetResource;
use App\Services\Interfaces\DatatableServiceInterface;
use App\Services\Interfaces\TimesheetServiceInterface;
use App\Http\Controllers\Staff\Controller as StaffController;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends StaffController
{
    protected $timesheetService;
    protected $datatableService;
    /**
     * TimesheetsController constructor.
     */
    public function __construct(TimesheetServiceInterface $timesheetService, DatatableServiceInterface $datatableService)
    {
        parent::__construct();
        $this->timesheetService = $timesheetService;
        $this->datatableService = $datatableService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('staff.timesheets.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff.timesheets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTimesheetRequest $request)
    {
        $user = Auth::user();

        if ($this->timesheetService->createTimesheet($user, $request)) {
            return redirect()->route('timesheets.create')->with(['success' => 'Create successfully!!!']);
        }

        abort(500, "Can't create timesheet!!!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timesheet = $this->timesheetService->getTimesheetByIdAndUserId($id, Auth::id())->first();

        if ($timesheet) {
            return view('staff.timesheets.show', compact('timesheet'));
        }

        abort(404, "URL doesn't exist!!!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timesheet = $timesheet = $this->timesheetService->getTimesheetByIdAndUserId($id, Auth::id())->first();
        if ($timesheet) {
            $timesheet = new TimesheetResource($timesheet);

            return view('staff.timesheets.edit', compact('timesheet'));
        }
        abort(404, "URL doesn't exist!!!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateTimesheetRequest $request, $id)
    {
        $timesheet = $timesheet = $this->timesheetService->getTimesheetByIdAndUserId($id, Auth::id())->first();
        if ($this->timesheetService->updateTimesheet($timesheet, $request)) {
            return redirect()->route('timesheets.edit', $id)->with(['success' => 'Edit successfully']);
        }

        abort(500, "Can't update timesheet!!!");
    }

    public function getTimesheets()
    {
        $timesheets = $this->timesheetService->getTimesheetByUserId(Auth::id());

        return $this->datatableService->timesheets($timesheets);
    }
}
