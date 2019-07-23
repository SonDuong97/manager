<?php

namespace App\Models;

use App\Models\Model as AppModel;

class Timesheet extends AppModel
{
    protected $table = 'timesheets';
    protected $fillable = [
        'date',
        'trouble',
        'plan_of_next_day',
        'user_id',
        'approved',
        'created_at',
        'modified_at',
        'deleted_at',
    ];

    /**
     * List of columns
     * @var string
     */
    const COL_USER_ID = 'user_id';
    const COL_APPROVED = 'approved';

    /**
     * List of status timesheet
     * @var integer
     */
    const NOT_APPROVED = 0;
    const APPROVED = 1;

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'timesheet_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
