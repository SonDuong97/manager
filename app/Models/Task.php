<?php

namespace App\Models;

use App\Models\Model as AppModel;

class Task extends AppModel
{
    protected $table = 'tasks';
    protected $fillable = [
        'content',
        'used_time',
        'timesheet_id',
        'created_at',
        'modified_at',
        'deleted_at',
    ];
}
