<?php

namespace App\Models;

use App\Models\Model as AppModel;

class Setting extends AppModel
{
    protected $table = 'settings';
    protected $fillable = [
        'name',
        'value',
        'created_at',
        'modified_at',
        'deleted_at',
    ];

    /**
     * List of columns
     * @var string
     */
    public const COL_START_TIMESHEET = 'start_time';
    public const COL_END_TIMESHEET = 'end_time';
    public const COL_VALUE = 'value';
    public const COL_NAME = 'name';
}
