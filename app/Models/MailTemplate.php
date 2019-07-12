<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
    protected $table = 'mail_templates';
    protected $fillable = ['template_name', 'action', 'sender', 'cc', 'bcc', 'title', 'message'];
    public $timestamps = true;

    public const ACTION_COL = 'action';

    public const ACTION_TIME_TO_CREATE_TIMESHEET = 'ACTION_TIME_TO_CREATE_TIMESHEET';
    public const ACTION_DEADLINE_TO_CREATE_TIMESHEET = 'ACTION_DEADLINE_TO_CREATE_TIMESHEET';
    public const ACTION_STAFF_CREATED_TIMESHEET = 'ACTION_STAFF_CREATED_TIMESHEET';
    public const ACTION_STAFF_EDITED_TIMESHEET = 'ACTION_STAFF_EDITED_TIMESHEET';
}
