<?php

namespace App\Models;

use App\Models\Model as AppModel;

class SummaryLog extends AppModel
{
    protected $table = 'summary_logs';
    protected $fillable = [
        'registed_time',
        'delayed_time',
        'from_date',
        'to_date',
        'user_id',
        'created_at',
        'modified_at',
        'deleted_at',
    ];

    /**
     * List of columns
     * @var string
     */
    const COL_USER_ID = 'user_id';
    const COL_FROM_DATE = 'from_date';
    const COL_TO_DATE = 'to_date';
}
