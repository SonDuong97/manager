<?php

namespace App\Http\Controllers\Staff;

use App\Http\Requests\Timesheet\CreateTimesheetRequest;
use App\Http\Resources\Staff\TimesheetResource;
use App\Models\MailTemplate;
use App\Models\Role;
use App\Services\Interfaces\DatatableServiceInterface;
use App\Services\Interfaces\TimesheetServiceInterface;
use App\Http\Controllers\Staff\Controller as StaffController;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends StaffController
{
    protected $timesheetService;
    protected $datatableService;

    /**
     * TimesheetsController constructor.
     */
    public function __construct(TimesheetServiceInterface $timesheetService,
                                DatatableServiceInterface $datatableService
    )
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
//        if ($this->timesheetService->isDone(Auth::id())) {
//            return redirect()->back()->withErrors([
//                'message' => 'You did timesheet today!!!',
//            ]);
//        }
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

        if ($this->timesheetService->isDone(Auth::id(), $request->input('date'))) {
            return redirect()->route('timesheets.create')->withErrors([
                'message' => 'You did timesheet in'.$request->input('date').'!!!',
            ]);
        }

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
        $timesheets = $this->timesheetService->getTimesheetsByUserId(Auth::id());

        return $this->datatableService->timesheets($timesheets);
    }

    public function getTimesheetsGroupByWeek()
    {
        $timesheets = $this->timesheetService->getTimesheetsByUserIdGroupByWeek(Auth::id());

        return response()->json([
            'data' => $timesheets,
        ]);
    }

    public function getTimesheetsGroupByMonth()
    {
        $timesheets = $this->timesheetService->getTimesheetsByUserIdGroupByMonth(Auth::id());

        return response()->json([
            'data' => $timesheets,
        ]);
    }

//    public function sendMailNotification($indexAction)
//    {
//        $actions = array([
//            1 => MailTemplate::ACTION_TIME_TO_CREATE_TIMESHEET,
//            2 => MailTemplate::ACTION_DEADLINE_TO_CREATE_TIMESHEET,
//        ]);
//        $this->timesheetService($this->userService->getUsersByRoleName(Role::ROLE_STAFF), $actions[$indexAction]);
//    }
}
