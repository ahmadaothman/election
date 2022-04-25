<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(){
        $data = array();
        
        return view('user_list',$data);
    }

    public function add(Request $request){
        $data = array();
        
        $data['districts'] = DB::select("SELECT DISTINCT district FROM electors");

        return view('user_form',$data);
    }

    public function edit(Request $request){
        $data = array();

        return view('user_form',$data);
    }

   
}
