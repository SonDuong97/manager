<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';
    public $timestamps = true;

    public const START_TIMESHEET_COL = 'start_time';
    public const END_TIMESHEET_COL = 'end_time';
}
