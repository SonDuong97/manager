<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\Admin\UserResource;
use App\Services\Interfaces\RoleServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller as AdminController;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\CreateUserRequest;

class UsersController extends AdminController
{
    protected $userService;
    protected $roleService;
    /**
     * UsersController constructor.
     */
    public function __construct(UserServiceInterface $userService, RoleServiceInterface $roleService)
    {
        parent::__construct();
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select([
            'id',
            'username',
            'email',
            'role_id',
            'manager_id',
        ]);
        return DataTables::of($users)
            ->addColumn('manager', function($user) {
                if (isset($user->manager)) {
                    return $user->manager->username;
                } else {
                    return 'N/A';
                }
            })
            ->addColumn('role', function ($user) {
                if (isset($user->role)) {
                    return $user->role->role_name;
                } else {
                    return 'N/A';
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where(Role::COL_ROLE_NAME, '!=', Role::ROLE_ADMIN)->get();
        $managers = Role::where(Role::COL_ROLE_NAME, Role::ROLE_MANAGER)->first()->user;

        return view('admin.users.create', compact('roles', 'managers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        if ($this->userService->createUser($request)) {
            return redirect()->route('users.create')->with(['success' => 'Successfully!!!']);
        } else {
            return redirect()->route('users.create')->withErrors([
                'message' => 'Create user failed!!!',
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = $this->roleService->getRoleListExceptAdminRole();
        if ($user && $roles) {
            return view('admin.users.edit', compact('user', 'roles'));
        }

        abort(500, 'User not found!!!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditUserRequest $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            if ($this->userService->updateUser($user, $request)) {
                return redirect()->route('users.edit', $id)->with(['success' => 'Edit successfully!!!']);
            }
        }

        abort(500, 'User not found!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($this->userService->deleteUser($user)) {
            return response()->json([
                'success' => 'Record has been deleted successfully!',
            ]);
        }

        abort(500, 'Cannot delete');
    }
}
