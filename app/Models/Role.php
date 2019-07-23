<?php

namespace App\Models;

use App\Models\Model as AppModel;

class Role extends AppModel
{
    protected $table = 'roles';
    protected $fillable = [
        'role_name',
        'created_at',
        'modified_at',
        'deleted_at',
    ];

    /**
     * List of columns
     * @var string
     */
    public const COL_ROLE_NAME = 'role_name';

    /**
     * List of roles
     * @var string
     */
    const ROLE_STAFF = 'staff';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';

    public function user() {
        return $this->hasMany('App\Models\User');
    }
}
