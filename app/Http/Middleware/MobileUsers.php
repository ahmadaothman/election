<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MobileUsers
{

    public function handle(Request $request, Closure $next)
    {
        $position = auth()->user()->position;
       
        if($position == 'fixed'){
            return redirect()->route('electors_mobile');

        }else{
            
            return $next($request);
        }
        
    }
}
