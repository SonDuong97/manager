<?php


namespace App\Services;

use App\Models\SummaryLog;
use App\Services\Interfaces\SummayLogServiceInterface;
use App\Services\Service as BaseService;

/**
 * Class SummaryLogService
 * @package App\Services
 */
class SummaryLogService extends BaseService implements SummayLogServiceInterface
{
    /**
     * Get log of user
     *
     * @param $userID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogByUserID($userID)
    {
        $logs = SummaryLog::where(SummaryLog::COL_USER_ID, $userID)->get();

        return response()->json($logs);
    }
}
