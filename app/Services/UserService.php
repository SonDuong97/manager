<?php


namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use App\Services\Service as BaseService;
use Illuminate\Http\Request;

class UserService extends BaseService implements UserServiceInterface
{
    public function createUser(Request $request)
    {
        $avatarFileName = "";
        if($request->hasFile('avatar')) {
            $avatarFileName .= time().'.'.$request->avatar->getClientOriginalExtension();
            $request->avatar->move(public_path('staff-assets/dist/img/'), $avatarFileName);
        }

        return User::create([
                'username' => $request->input('username'),
                'password' => $request->input('password'),
                'email' => $request->input('email'),
                'avatar' => $avatarFileName == ""?:'/staff-assets/dist/img/'.$avatarFileName,
                'description' => $request->input('description'),
                'role_id' => $request->input('role'),
                'manager_id' => $request->input('manager'),
            ]);
    }

    public function updateUser(User $user, Request $request)
    {
        return $user->update([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role'),
        ]);
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }

    public function getUsersByRoleName($roleName)
    {
        return Role::with('user')->where(Role::COL_ROLE_NAME, $roleName)->first()->user;
    }
}
