<?php

namespace App\Models;

use App\Models\Model as AppModel;

class MailTemplate extends AppModel
{
    protected $table = 'mail_templates';
    protected $fillable = [
        'template_name',
        'action',
        'sender',
        'cc',
        'bcc',
        'title',
        'message',
        'created_at',
        'modified_at',
        'deleted_at',
    ];

    /**
     * List of columns
     */
    const COL_ACTION = 'action';

    /**
     * List of actions
     * @var string
     */
    const ACTION_TIME_TO_CREATE_TIMESHEET = 'ACTION_TIME_TO_CREATE_TIMESHEET';
    const ACTION_DEADLINE_TO_CREATE_TIMESHEET = 'ACTION_DEADLINE_TO_CREATE_TIMESHEET';
    const ACTION_STAFF_CREATED_TIMESHEET = 'ACTION_STAFF_CREATED_TIMESHEET';
    const ACTION_STAFF_EDITED_TIMESHEET = 'ACTION_STAFF_EDITED_TIMESHEET';
}
