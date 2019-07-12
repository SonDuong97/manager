<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['role_name'];
    public $timestamps = true;
    protected $dates = ['created_at', 'modified_at', 'deleted_at'];

    public const COL_ROLE_NAME = 'role_name';
    public const ROLE_STAFF = 'staff';
    public const COL_ID = 'id';

    public function user() {
        return $this->hasMany('App\Models\User');
    }
}
