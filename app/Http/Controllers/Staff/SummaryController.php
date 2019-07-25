<?php

namespace App\Http\Controllers\Staff;

use App\Models\SummaryLog;
use App\Http\Controllers\Staff\Controller as StaffController;
use App\Services\Interfaces\SummayLogServiceInterface;
use Illuminate\Support\Facades\Auth;

class SummaryController extends StaffController
{
    protected $summaryService;
    /**
     * SummaryController constructor.
     */
    public function __construct(SummayLogServiceInterface $summaryService)
    {
        $this->summaryService = $summaryService;
    }

    public function showDashboard() {
        return view('staff.dashboard');
    }

    public function getLog()
    {
        return $this->summaryService->getLogByUserID(Auth::id());
    }
}
