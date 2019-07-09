<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $table = 'timesheets';
    protected $fillable = ['date', 'trouble', 'plan_of_next_day', 'user_id'];
    public $timestamps = true;

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'timesheet_id', 'id');
    }
}
