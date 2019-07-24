<?php


namespace App\Services;

use App\Mail\SystemMail;
use App\Models\MailTemplate;
use App\Models\Setting;
use App\Models\SummaryLog;
use App\Models\Task;
use App\Models\Timesheet;
use App\Models\User;
use App\Services\Interfaces\TimesheetServiceInterface;
use App\Services\Service as BaseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TimesheetService extends BaseService implements TimesheetServiceInterface
{
    public function createTimesheet(User $user, Request $request)
    {
        return DB::transaction(function () use ($user, $request) {
            $timesheet = Timesheet::create([
                'date' => date('Y-m-d', strtotime($request->input('date'))),
                'trouble' => $request->input('trouble'),
                'plan_of_next_day' => $request->input('plan'),
                'user_id' => $user->id,
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
            $summary = SummaryLog::where(SummaryLog::COL_FROM_DATE, '<=', $now->toDateString())
                ->where(SummaryLog::COL_TO_DATE, '>=', $now->toDateString())
                ->where(SummaryLog::COL_USER_ID, $user->id)
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
                    'user_id' => $user->id,
                ]);
            }
            // Send mail to manager
            if ($user->manager) {
                $emailManager = $user->manager->email;
                Mail::to($emailManager)
                    ->queue(new SystemMail(MailTemplate::ACTION_STAFF_CREATED_TIMESHEET));
            }

            return $timesheet;
        }, 3);
    }

    public function updateTimesheet(Timesheet $timesheet, Request $request)
    {
        return DB::transaction(function () use ($timesheet, $request) {
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
                        'timesheet_id' => $timesheet->id,
                    ]);
                }
            }

            //Send mail to manager
            if ($timesheet->approved == Timesheet::APPROVED) {
                $emaiManager = $timesheet->user->manager->email;
                if ($emaiManager) {
                    Mail::to($emaiManager)
                        ->queue(new SystemMail(MailTemplate::ACTION_STAFF_EDITED_TIMESHEET));
                }
            }

            return $timesheet;
        }, 3);
    }

    public function approveTimesheet(Timesheet $timesheet)
    {
        return $timesheet->update([
            Timesheet::COL_APPROVED => Timesheet::APPROVED,
        ]);
    }

    public function isDelayed()
    {
        $timeNow = Carbon::now()->toTimeString();
        $endTime = Setting::select(Setting::COL_VALUE)
            ->where(Setting::COL_NAME, Setting::COL_END_TIMESHEET)
            ->first()
            ->value;
        if ($timeNow > $endTime) {
            return true;
        }

        return false;
    }
}
