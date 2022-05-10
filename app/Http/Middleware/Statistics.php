<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
class Statistics
{
    
    public function handle(Request $request, Closure $next)
    {
        $position = auth()->user()->position;
        
        $allowed = array('statistic','statistic_voters','statistic_doctrine','statistic_towns','statistic_sex','results');
        $name = Route::currentRouteName();

        if($position == 'statistics' && !in_array($name,$allowed)){
            return redirect()->route('statistic');
        }else{
            
            return $next($request);
        }
    }
}
