<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $fillable = ['content', 'used_time', 'timesheet_id'];
    public $timestamps = true;
}
