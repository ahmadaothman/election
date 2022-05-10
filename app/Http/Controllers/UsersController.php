<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('mobile_user');
        $this->middleware('statistics');
    }

    public function list(){
        $data = array();
        $data['users'] = User::where('position','fixed')->get();

        return view('user_list',$data);
    }

    public function add(Request $request){
        $data = array();
        
        $data['districts'] = DB::select("SELECT DISTINCT district FROM electors");

        if($request->method() == 'POST'){
            $validation = $request->validate([
                'name'      =>  'required|min:4|unique:users',
                'email'     =>  'required|unique:users|email',
                'telephone' =>  'required|unique:users|min:7',
                'password'  =>  'required|min:5'
            ]);

            $user_data = [
                'name'              =>  $request->input('name'),
                'email'             =>  $request->input('email'),
                'telephone'         =>  $request->input('telephone'),
                'district'          =>  $request->input('district'),
                'election_center'   =>  $request->input('election_center'),
                'ballot_pen'        =>  $request->input('ballot_pen'),
                'password'          =>  Hash::make($request->input('password')),
                'position'          =>  'fixed',
                'updated_at'        =>  now(),
                'created_at'        =>  now()
            ];
            User::insert($user_data);
            return redirect(route('users_list'))->with('status', 'Success: New user added!');
        }

        return view('user_form',$data);
    }

    public function edit(Request $request,$id){
        $data = array();
        
        $data['districts'] = DB::select("SELECT DISTINCT district FROM electors");

        $user = User::where('id',$id)->first();

        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['telephone'] = $user->telephone;
        $data['district'] = $user->district;
        $data['election_center'] = $user->election_center;
        $data['ballot_pen'] = $user->ballot_pen;

        $data['id'] = $user->id;

        if($request->method() == 'POST'){
            $validation_data = [
                'name'      =>  'required|min:4',
                'email'     =>  'required|email',
                'telephone' =>  'required|min:7',
            ];

            if(!empty($request->input('password'))){
                $validation_data['password'] = 'required|min:8';
            }

            $validation = $request->validate($validation_data);

            $user_data = [
                'name'              =>  $request->input('name'),
                'email'             =>  $request->input('email'),
                'telephone'         =>  $request->input('telephone'),
                'district'          =>  $request->input('district'),
                'election_center'   =>  $request->input('election_center'),
                'ballot_pen'        =>  $request->input('ballot_pen'),
                'updated_at'        =>  now(),
            ];

            if(!empty($request->input('password'))){
                $user_data['password']  = Hash::make($request->input('password'));
            }

            User::where('id',$id)->update($user_data);
            return redirect(route('users_list'))->with('status', 'Success: User info updated!');
        }

        return view('user_form',$data);
    }

    public function delete(Request $request,$id){
        User::where('id',$id)->delete();
        return redirect(route('users_list'))->with('status', 'Success: User delete!');
    }
}
