<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
            if (Auth::user()->role->role_name == Role::ROLE_ADMIN) {
                return redirect()->route('admin.top');
            } else {
                return redirect()->route('staffs.dashboard');
            }
        }
//        switch ($guard)
//        {
//            case 'admin':
//                if (Auth::guard($guard)->check()) {
//                    return redirect()->route('admin.top');
//                }
//            break;
//            case 'staff':
//            default:
//                if (Auth::guard($guard)->check()) {
//                    return redirect()->route('staffs.top');
//                }
//            break;
//        }

        return $next($request);
    }
}
