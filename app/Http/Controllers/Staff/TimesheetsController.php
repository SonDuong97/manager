<?php

namespace App\Http\Controllers\Staff;

use App\Http\Requests\Timesheet\CreateTimesheetRequest;
use App\Http\Resources\Staff\TimesheetResource;
use App\Mail\SystemMail;
use App\Models\MailTemplate;
use App\Models\Role;
use App\Models\Setting;
use App\Models\SummaryLog;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;

class TimesheetsController extends Controller
{
    /**
     * TimesheetsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timesheets = Timesheet::where(Timesheet::COL_USER_ID, Auth::id());

        return DataTables::of($timesheets)
            ->make(true);
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
        $userID = Auth::id();
        $timesheet = Timesheet::create([
            'date' => date('Y-m-d', strtotime($request->input('date'))),
            'trouble' => $request->input('trouble'),
            'plan_of_next_day' => $request->input('plan'),
            'user_id' => $userID,
        ]);

        $tasks = $request->input('tasks');
        $taskNum = count($tasks['contents']);
        for ($i = 0; $i < $taskNum; $i++) {
            $task = Task::create([
                'content' => $tasks['contents'][$i],
                'used_time' => $tasks['hours'][$i],
                'timesheet_id' => $timesheet->id,
            ]);
        }

        //Summary logs
        $now = Carbon::now();

        $summary = SummaryLog::where('from_date', '<=', $now->toDateString())
                ->where('to_date', '>=', $now->toDateString())
                ->first();
        if ($summary) {
            $registed_time = $summary->registed_time + 1;
            $delayed_time = $summary->delayed_time;

            if ($this->isDelayed()) {
                $delayed_time++;
            }

            $summary->update([
                'registed_time' => $registed_time,
                'delayed_time' => $delayed_time,
            ]);
        } else {
            $start = Carbon::now()->startOfMonth()->toDateString();
            $end = Carbon::now()->endOfMonth()->toDateString();

            SummaryLog::create([
                'registed_time' => 1,
                'delayed_time' => $this->isDelayed() ? 1 : 0,
                'from_date' => $start,
                'to_date' => $end,
                'user_id' => Auth::id(),
            ]);
        }

        $manager = Auth::user()->manager->email;
        if (!$manager) {
            Mail::to($manager)
                ->queue(new SystemMail(MailTemplate::ACTION_STAFF_CREATED_TIMESHEET));
        }

        return redirect()->route('timesheets.create')->with(['success' => 'Create successfully!!!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timesheet = new TimesheetResource(Timesheet::where(Timesheet::COL_ID, $id)
                                                    ->where(Timesheet::COL_USER_ID, Auth::id())
                                                    ->first());

        return view('staff.timesheets.show', ['timesheet' => $timesheet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timesheet = Timesheet::where(Timesheet::COL_ID, $id)
                            ->where(Timesheet::COL_USER_ID, Auth::id())
                            ->first();
        if ($timesheet) {
            $timesheet = new TimesheetResource($timesheet);

            return view('staff.timesheets.edit', ['timesheet' => $timesheet]);
        }
        abort('404', 'Duong dan khong ton tai');
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
        $timesheet = Timesheet::where(Timesheet::COL_ID, $id)
            ->where(Timesheet::COL_USER_ID, Auth::id())
            ->first();
        if ($timesheet) {
            if ($timesheet->approved == Timesheet::APPROVED) {
                $manager = Auth::user()->manager->email;
                if (!$manager) {
                    Mail::to($manager)
                        ->queue(new SystemMail(MailTemplate::ACTION_STAFF_EDITED_TIMESHEET));
                }
            }

            $timesheet->update([
                'approved' => Timesheet::NOT_APPROVED,
                'date' => date('Y-m-d', strtotime($request->input('date'))),
                'trouble' => $request->input('trouble'),
                'plan_of_next_day' => $request->input('plan'),
            ]);

            $tasks = $request->input('tasks');
            foreach ($tasks['contents'] as $key => $value) {
                $task = Task::find($key);
                if($task) {
                    $task->update([
                        'content' => $value,
                        'used_time' => $tasks['hours'][$key],
                    ]);
                } else {
                    Task::create([
                        'content' => $value,
                        'used_time' => $tasks['hours'][$key],
                        'timesheet_id' => $id,
                    ]);
                }
            }

            return redirect('/staff/timesheets/'.$id.'/edit')->with(['success' => 'Edit successfully']);
        }
        abort('404', 'Khong tim thay trang');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showTimesheetList()
    {
        return view('staff.timesheets.index');
    }

    protected function isDelayed()
    {
        $timeNow = Carbon::now()->toTimeString();
        $endTime = Setting::select(Setting::COL_VALUE)
            ->where('name', Setting::COL_END_TIMESHEET)
            ->first()
            ->value;
        if ($timeNow > $endTime) {
            return true;
        }

        return false;
    }

    public function getLog()
    {
        $logs = SummaryLog::where(SummaryLog::COL_USER_ID, Auth::id())->get();
        return response()->json($logs);
    }
}
