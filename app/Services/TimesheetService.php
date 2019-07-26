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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class TimesheetService
 * @package App\Services
 */
class TimesheetService extends BaseService implements TimesheetServiceInterface
{
    /**
     * Create timesheet and send mail notification to manager and person who is received notification.
     *
     * @param User $user
     * @param Request $request
     * @return mixed
     */
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

            // Send mail to people who are notified notication.
            $receivedPeople = User::select(User::COL_EMAIL)->where(User::COL_NOTIFIED, User::TYPE_NOTIFIED)->get();
            if (count($receivedPeople) > 0) {
                foreach ($receivedPeople as $person) {
                    Mail::to($person->email)
                        ->queue(new SystemMail(MailTemplate::ACTION_STAFF_CREATED_TIMESHEET));
                }
            }
            return $timesheet;
        }, 3);
    }

    /**
     * Update timesheet and send mail notification to manager.
     *
     * @param Timesheet $timesheet
     * @param Request $request
     * @return mixed
     */
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

    /**
     * Update approved attribute become approved
     *
     * @param Timesheet $timesheet
     * @return bool
     */
    public function approveTimesheet(Timesheet $timesheet)
    {
        return $timesheet->update([
            Timesheet::COL_APPROVED => Timesheet::APPROVED,
        ]);
    }

    /**
     * Check if sending time is delayed or not.
     *
     * @return bool
     */
    public function isDelayed()
    {
        $timeNow = Carbon::now()->toTimeString();
        $endTime = Setting::select(Setting::COL_VALUE)
            ->where(Setting::COL_NAME, Setting::COL_NAME_END_TIMESHEET)
            ->first()
            ->value;
        if ($timeNow > $endTime) {
            return true;
        }

        return false;
    }

    /**
     * Get timesheets haven't been approved with user_id is is managed by a manager.
     *
     * @param $managerID
     * @return Builder
     */
    public function getTimesheetsNotApproved($managerID)
    {
        $timesheets = Timesheet::whereIn(Timesheet::COL_USER_ID, function ($query) use ($managerID) {
            $query->select(User::COL_ID)
                ->from('users')
                ->where(User::COL_MANAGER_ID, $managerID);
        })->where(Timesheet::COL_APPROVED, Timesheet::NOT_APPROVED);

        return $timesheets;
    }

    /**
     * Get timesheet by ID.
     *
     * @param $id
     * @return Builder
     */
    public function getTimesheetByID($id)
    {
        $timesheet = Timesheet::with('user')->where(Timesheet::COL_ID, $id);

        return $timesheet;
    }

    /**
     * Get timesheet by userId.
     * @param $userId
     * @return Builder
     */
    public function getTimesheetsByUserId($userId)
    {
        $timesheets = Timesheet::with('user')->where(Timesheet::COL_USER_ID, $userId);

        return $timesheets;
    }

    /**
     * Get timsheet by id and userId.
     *
     * @param $id
     * @param $userId
     * @return Builder
     */
    public function getTimesheetByIdAndUserId($id, $userId)
    {
        return $this->getTimesheetByID($id)->union($this->getTimesheetsByUserId($userId));
    }

    /**
     * Get timesheet by userId group by week.
     *
     * @param $userId
     * @return Collection
     */
    public function getTimesheetsByUserIdGroupByWeek($userId)
    {
        return $this->getTimesheetsByUserId($userId)->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('W');
        });
    }

    /**
     * Get timesheet by userId group by month.
     *
     * @param $userId
     * @return Collection
     */
    public function getTimesheetsByUserIdGroupByMonth($userId)
    {
        return $this->getTimesheetsByUserId($userId)->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('Y-m');
        });
    }

    /**
     * Check if staff did timesheet today or not
     *
     * @param $userId
     * @return bool
     */
    public function isDone($userId)
    {
        $timesheet = Timesheet::where(Timesheet::COL_USER_ID, $userId)
            ->whereRaw("CAST(created_at AS DATE) = '".Carbon::now()->toDateString()."'")->first();

        if ($timesheet) {
            return true;
        }
        return false;
    }
}
