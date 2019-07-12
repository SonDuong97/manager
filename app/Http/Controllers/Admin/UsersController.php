<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\CreateUserRequest;

class UsersController extends Controller
{
    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth', 'check.role']);
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
        $roles = Role::where('role_name', '!=', 'admin')->get();
        $managers = Role::where('role_name', 'leader')->first()->user;

        return view('admin.users.create', ['roles'=>$roles, 'managers'=>$managers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = User::create([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'email' => $request->input('email'),
            'avatar' => $request->input('avatar'),
            'description' => $request->input('description'),
            'role_id' => $request->input('role'),
            'manager_id' => $request->input('manager'),
        ]);

        if ($user) {
            return redirect()->route('users.create')->with(['success' => 'Successfully!!!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        if (!$user) {
            abort('404');
        }
        $roles = Role::where('role_name', '!=', 'admin')->get();
        return view('admin.users.edit', ['user' => $user, 'roles' => $roles]);
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
        if (!$user) {
            abort('404');
        }

        if ($user->update($request->all())) {
            return redirect('admin/users/'.$id.'/edit')->with(['success' => 'Edit successfully!!!']);
        }
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
        $user->delete();

        return response()->json([
            'success' => 'Record has been deleted successfully!'
        ]);
    }
}
