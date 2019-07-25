<?php


namespace App\Services;

use App\Models\Role;
use App\Services\Interfaces\RoleServiceInterface;
use App\Services\Service as BaseService;
use Illuminate\Database\Eloquent\Collection;

class RoleService extends BaseService implements RoleServiceInterface
{
    /**
     * Get roles except admin role.
     *
     * @return Collection
     */
    public function getRoleListExceptAdminRole ()
    {
        return Role::where(Role::COL_ROLE_NAME, '!=', Role::ROLE_ADMIN)->get();
    }
}
