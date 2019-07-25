<?php


namespace App\Services;

use App\Models\Setting;
use App\Services\Interfaces\SettingServiceInterface;
use App\Services\Service as BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class SettingService
 * @package App\Services
 */
class SettingService extends BaseService implements SettingServiceInterface
{
    /**
     * Update the time to start creating timesheet.
     *
     * @param Setting $setting
     * @param Request $request
     * @return bool
     */
    public function updateSettingStartCreatingTimesheet(Setting $setting, Request $request)
    {
        return $setting->update([
            'value' => $request->input('startTimesheet'),
        ]);
    }

    /**
     * Update the time to end creating timesheet.
     *
     * @param Setting $setting
     * @param Request $request
     * @return bool
     */
    public function updateSettingEndCreatingTimesheet(Setting $setting, Request $request)
    {
        return $setting->update([
            'value' => $request->input('endTimesheet'),
        ]);
    }

    /**
     * Update both the time to start creating timesheet and the time to end creating timesheet.
     *
     * @param Setting $startingTime
     * @param Setting $endingTime
     * @param Request $request
     * @return mixed
     */
    public function updateSettingTimeTimesheet(Setting $startingTime, Setting $endingTime, Request $request)
    {
        return DB::transaction(function () use ($startingTime, $endingTime, $request) {
            return $this->updateSettingStartCreatingTimesheet($startingTime, $request)
                && $this->updateSettingEndCreatingTimesheet($endingTime, $request);
        }, 3);
    }

    /**
     * Get setting by name
     *
     * @param $name
     * @return Collection
     */
    public function getSettingByName($name)
    {
        return Setting::where(Setting::COL_NAME, $name)->first();
    }
}
