<?php


namespace App\Services;

use App\Models\Setting;
use App\Services\Interfaces\SettingServiceInterface;
use App\Services\Service as BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingService extends BaseService implements SettingServiceInterface
{
    public function updateSettingStartCreatingTimesheet(Setting $setting, Request $request)
    {
        return $setting->update([
            'value' => $request->input('startTimesheet'),
        ]);
    }

    public function updateSettingEndCreatingTimesheet(Setting $setting, Request $request)
    {
        return $setting->update([
            'value' => $request->input('endTimesheet'),
        ]);
    }

    public function updateSettingTimeTimesheet(Setting $startingTime, Setting $endingTime, Request $request)
    {
        return DB::transaction(function () use ($startingTime, $endingTime, $request) {
            return $this->updateSettingStartCreatingTimesheet($startingTime, $request)
                && $this->updateSettingEndCreatingTimesheet($endingTime, $request);
        }, 3);
    }
}
