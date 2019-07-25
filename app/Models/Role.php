<?php

namespace App\Models;

use App\Models\Model as AppModel;
use Illuminate\Database\Eloquent\Collection;

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

    /**
     * Get roles except admin
     *
     * @return Collection
     */
    public static function getRolesExceptAdminRole()
    {
        return Role::where(self::COL_ROLE_NAME, '!=', self::ROLE_ADMIN)->get();
    }

    public static function getRoleByName($roleName)
    {
        return Role::with('user')->where(Role::COL_ROLE_NAME, $roleName)->first()->user;
    }
}
