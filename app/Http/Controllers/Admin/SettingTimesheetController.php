<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Controller as AdminController;
use App\Http\Requests\Admin\TimeTimesheetRequest;
use App\Models\Setting;
use App\Services\Interfaces\SettingServiceInterface;

class SettingTimesheetController extends AdminController
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
        $startTimesheet = $this->settingService->getSettingByName(Setting::COL_NAME_START_TIMESHEET);
        $endTimesheet = $this->settingService->getSettingByName(Setting::COL_NAME_END_TIMESHEET);
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
        $startTimesheet = $this->settingService->getSettingByName(Setting::COL_NAME_START_TIMESHEET);
        $endTimesheet = $this->settingService->getSettingByName(Setting::COL_NAME_END_TIMESHEET);

        if ($this->settingService->updateSettingTimeTimesheet($startTimesheet, $endTimesheet, $request)) {
            return redirect()->route('settings.timesheets.edit')->with(['success' => 'Edit successfully!!!']);
        } else {
            return redirect()->route('settings.timesheets.edit')->withErrors([
                'message' => 'Edit failed',
            ]);
        }
    }
}
