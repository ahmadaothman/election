<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Electors;
use App\Models\Concadidates;
use App\Models\Votes;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class MobileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('statistics');
    }

    public function electorsMobile(Request $request){
        $data = array();

        $user = User::where('id',Auth::id())->first();

        $data['user'] = $user;

        return view('electors_mobile',$data);
    }

    public function getElectorsByUser(Request $request){
        $user = User::where('id',Auth::id())->first();

        $electors = Electors::where('election_country','=','')
        ->where('district',$user->district)
        ->where('election_center',$user->election_center)
        ->where('ballot_pen',$user->ballot_pen)
        ->orderByRaw("CAST(virtual_number as UNSIGNED) ASC")
        ->get();


        return $electors;
    }

    public function doneMobile(Request $request){
        Electors::where('id',$request->post('id'))->update(['done'=>1]);

        return array('success'=>true);
    }

    public function vote(Request $request){
        if($request->method() == 'POST'){
            $user = User::where('id',Auth::id())->first();

            $data['user'] = $user;

            $sql = "INSERT INTO votes SET election_center='" . $user->election_center . "',district='" . $user->district . "',ballot_pen='" . $user->ballot_pen . "',candidate_id='" . (int)$request->post('id') . "',user_id='" . Auth::id() . "',is_country=0";
            DB::insert($sql);
        }else{
            $sql = "SELECT * FROM candidates ORDER BY sort_order";
            $data = array();
            $data['candidates'] = DB::select($sql);
            return view('vote',$data);
        }
    }
}
