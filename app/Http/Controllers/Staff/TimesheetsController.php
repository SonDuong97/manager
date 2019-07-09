<?php

namespace App\Http\Controllers\Staff;

use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Timesheet\CreateTimesheetRequest;
use App\Http\Resources\Staff\TimesheetResource;
use App\Models\Task;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TimesheetsController extends Controller
{
    /**
     * TimesheetsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timesheet = Timesheet::find($id);
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
        $timesheet = Timesheet::find($id);
        if ($timesheet) {
            $timesheet->update([
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
}
