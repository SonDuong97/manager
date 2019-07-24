<?php


namespace App\Services;

use App\Models\User;
use App\Services\Interfaces\DatatableServiceInterface;
use App\Services\Service as BaseService;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTables;

class DatatableService extends BaseService implements DatatableServiceInterface
{
    public function users()
    {
        $users = User::select([
            'id',
            'username',
            'email',
            'role_id',
            'manager_id',
        ]);
        return DataTables::of($users)
            ->addColumn('manager', function($user) {
                if (isset($user->manager)) {
                    return $user->manager->username;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('role', function ($user) {
                if (isset($user->role)) {
                    return $user->role->role_name;
                } else {
                    return 'N/A';
                }
            })
            ->make(true);
    }

    public function timesheets(Builder $timesheets)
    {
        return DataTables::of($timesheets)
            ->addColumn('username', function ($timesheet) {
                if ($timesheet->user) {
                    return $timesheet->user->username;
                } else {
                    return 'N/A';
                }
            })
            ->make(true);
    }


}
