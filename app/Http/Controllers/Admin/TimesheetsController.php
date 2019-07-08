<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TimeTimesheetRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimesheetsController extends Controller
{

    /**
     * TimesheetController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function edit()
    {
        $startTimesheet = Setting::where('name', Setting::START_TIMESHEET_COL)->first();
        $endTimesheet = Setting::where('name', Setting::END_TIMESHEET_COL)->first();
        return view(
            'admin.settings.timesheets.edit',
            [
                'startTimesheet' => $startTimesheet,
                'endTimesheet' => $endTimesheet,
            ]
        );
    }

    public function update(TimeTimesheetRequest $request)
    {
        $startTimesheet = Setting::where('name', Setting::START_TIMESHEET_COL)->first();
        $endTimesheet = Setting::where('name', Setting::END_TIMESHEET_COL)->first();

        $startTimesheet->value = $request->input('startTimesheet');
        $endTimesheet->value = $request->input('endTimesheet');

        $startTimesheet->save();
        $endTimesheet->save();

        return redirect()->route('settings.timesheets.edit')->with(['success' => 'Edit successfully!!!']);
    }
}
