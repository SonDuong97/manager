<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AfterLoginCheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!Auth::check()) {
            return redirect()->route('login');
        } else {
            if (Auth::user()->role->role_name == 'admin') {
                return redirect()->route('admin.top');
            }
        }

        return $response;
    }
}
