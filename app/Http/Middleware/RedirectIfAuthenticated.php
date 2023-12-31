<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            
            if (Auth::guard($guard)->check()) {
                $role = Auth::user()->role;


                if(in_array($role, ['ADMINISTRATOR', 'ADMINSTAFF', 'REQUESTING PARTY', 'APPROVING OFFICER'])){
                    return redirect('/dashboard');
                }
               

                if($role === 'ATTENDEE'){
                    return redirect('/event-feeds');
                }

                //return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
