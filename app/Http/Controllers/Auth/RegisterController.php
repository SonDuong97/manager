<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Admin\CreateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\RoleServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/staff';
    protected $roleService;
    protected $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoleServiceInterface $roleService, UserServiceInterface $userService)
    {
        $this->middleware('guest');
        $this->roleService = $roleService;
        $this->userService = $userService;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $roles = $this->roleService->getRoleListExceptAdminRole();
        $managers = $this->userService->getUsersByRoleName(Role::ROLE_MANAGER);
        return view('auth.register', compact('roles', 'managers'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'description' => ['required', 'max:1024'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'description' => $data['description'],
            'role_id' => Role::where(Role::COL_ROLE_NAME, 'staff')->first()->id,
        ]);
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
           return redirect()->back()->with([
               'success' => 'Successfully!!!',
           ]);
       }

       abort(500, "Can't create user!!!");
    }
}
