<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EditUserRequest;
use App\Services\Interfaces\DatatableServiceInterface;
use App\Services\Interfaces\RoleServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use App\Http\Controllers\Admin\Controller as AdminController;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\Admin\CreateUserRequest;

class UserController extends AdminController
{
    protected $userService;
    protected $roleService;
    protected $datatableService;
    /**
     * UsersController constructor.
     */
    public function __construct(
        UserServiceInterface $userService,
        RoleServiceInterface $roleService,
        DatatableServiceInterface $datatableService
    )
    {
        parent::__construct();
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->datatableService = $datatableService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->roleService->getRoleListExceptAdminRole();
        $managers = $this->userService->getUserByRoleName(Role::ROLE_MANAGER);

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

    public function getUsers()
    {
        return $this->datatableService->users();
    }
}
