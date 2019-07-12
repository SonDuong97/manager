<?php

namespace App\Console;

use App\Mail\SystemMail;
use App\Models\MailTemplate;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        $schedule->call(function () {
            $idRoleStaff = Role::select(Role::COL_ID)
                ->where(Role::COL_ROLE_NAME, Role::ROLE_STAFF)
                ->first()
                ->id;
            $staffs = User::select('email')->where(User::COL_ROLE_ID, $idRoleStaff)->get();

            foreach ($staffs as $staff) {
                Mail::to($staff->email)
                    ->queue(new SystemMail(MailTemplate::ACTION_TIME_TO_CREATE_TIMESHEET));
            }
        })->dailyAt(Setting::select(Setting::VALUE_COL)
                            ->where(Setting::NAME_COL, Setting::START_TIMESHEET_COL)
                            ->first()
                            ->value);

        $schedule->call(function () {
            $idRoleStaff = Role::select(Role::COL_ID)
                ->where(Role::COL_ROLE_NAME, Role::ROLE_STAFF)
                ->first()
                ->id;
            $staffs = User::select('email')->where(User::COL_ROLE_ID, $idRoleStaff)->get();

            foreach ($staffs as $staff) {
                Mail::to($staff->email)
                    ->queue(new SystemMail(MailTemplate::ACTION_DEADLINE_TO_CREATE_TIMESHEET));
            }
        })->dailyAt(Setting::select(Setting::VALUE_COL)
            ->where(Setting::NAME_COL, Setting::END_TIMESHEET_COL)
            ->first()
            ->value);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
