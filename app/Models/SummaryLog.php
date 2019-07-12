<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryLog extends Model
{
    protected $table = 'summary_logs';
    protected $fillable = ['registed_time', 'delayed_time', 'from_date', 'to_date', 'user_id'];
    public $timestamps = true;
    protected $dates = ['from_date', 'to_date', 'created_at', 'modified_at'];
}
