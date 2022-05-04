<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

   /* public function handle(Request $request, Closure $next)
    {
        $position = auth()->user()->position;
       
        if($position == 'fixed'){
            return redirect()->route('electors_by_user');

        }else{
            return $next($request);
        }
        
    }*/

    protected function redirectTo($request)
    {
       

        if (! $request->expectsJson()) {
            return route('login');
        }

       
    }
}
