<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'timesheets';
    protected $fillable = ['date', 'trouble', 'plan_of_next_day', 'user_id'];
    public $timestamps = true;
    protected $dates = ['created_at', 'modified_at'];

    public const USER_ID_COL = 'user_id';
    public const ID_COL = 'id';
    public const COL_APPROVED = 'approved';
    public const NOT_APPROVED = 0;
    public const APPROVED = 1;

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'timesheet_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
