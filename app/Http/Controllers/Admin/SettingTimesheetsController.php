<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller as AdminController;
use App\Http\Requests\Admin\TimeTimesheetRequest;
use App\Models\Setting;
use App\Services\Interfaces\SettingServiceInterface;

class SettingTimesheetsController extends AdminController
{
    protected $settingService;
    /**
     * TimesheetController constructor.
     */
    public function __construct(SettingServiceInterface $settingService)
    {
        parent::__construct();
        $this->settingService = $settingService;
    }

    public function edit()
    {
        $startTimesheet = Setting::where(Setting::COL_NAME, Setting::COL_START_TIMESHEET)->first();
        $endTimesheet = Setting::where(Setting::COL_NAME, Setting::COL_END_TIMESHEET)->first();
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
        $startTimesheet = Setting::where(Setting::COL_NAME, Setting::COL_START_TIMESHEET)->first();
        $endTimesheet = Setting::where(Setting::COL_NAME, Setting::COL_END_TIMESHEET)->first();

        if ($this->settingService->updateSettingTimeTimesheet($startTimesheet, $endTimesheet, $request)) {
            return redirect()->route('settings.timesheets.edit')->with(['success' => 'Edit successfully!!!']);
        } else {
            return redirect()->route('settings.timesheets.edit')->withErrors([
                'message' => 'Edit failed',
            ]);
        }
    }
}
