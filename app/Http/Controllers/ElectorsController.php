<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Electors;
use App\Models\Concadidates;
use App\Models\Votes;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ElectorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = array();
        $data['districts'] = Electors::distinct()->get(['district']);

        return view('electors',$data);
    }

    public function get(Request $request)
    {
        if($request->method() == 'POST'){
            foreach($request->post('data') as $data){

                Electors::where('id',$data['id'])->update(['virtual_number'=>$data['value']]);
            }
        }
        return Electors::where('district',$request->get('district'))->get();
    }

    public function saveElectionCenter(Request $request){
        $data = json_decode(stripslashes($_POST['data']));
        
        foreach($data->electors as $elector){
            Electors::where('id',$elector->id)->update([
                'ballot_pen'=>$data->ballot_pen,
                'election_center'=>$data->election_center
            ]);
        }
        
        return response()->json(['success'=>true,'message'=>$data->election_center,'test'=>$data]);
    }

    public function electoresNumbers(){

        $data = json_decode(stripslashes($_POST['data']));
        $i = 1;

        foreach($data->electors as $elector){
            Electors::where('id',$elector->id)->update([
                'virtual_number'=>$i,
            ]);
            $i++;
        }
        
        return response()->json(['success'=>true,'message'=>'','test'=>$data]);
    }

    public function edit(Request $request,$id){
        $data = array();
        $data['heading_title'] = 'تعديل معلومات الناخب';
        $data['elector'] = Electors::where('id',$id)->first();
        $data['concadidates'] = Concadidates::get();

        if ($request->isMethod('post')) {
            
            Electors::where("id",$id)->update([
                'telephone'                 => $request->input('telephone'),
                'elected_last_election'     => $request->input('elected_last_election'),
                'election_center'           => $request->input('election_center'),
                'ballot_pen'                => $request->input('ballot_pen'),
                'elected_last_election'     => $request->input('elected_last_election'),
                'preferential_vote'         => $request->input('preferential_vote'),
            ]);
            
            return redirect()->route('electors')->with('status', 'تم تعديل معلومات الناخب!');
        }

        return view('elector_form',$data);
    }

    public function done(Request $request){
        Electors::where('id',$request->post('id'))->update(['done'=>1]);

    }

    public function doneMobile(Request $request){
        Electors::where('id',$request->post('id'))->update(['done'=>1]);

    }

    public function sortCountries(Request $request){
        $data = array();
        
        $sql = "SELECT DISTINCT (election_country) as country FROM electors WHERE election_country!=''";

        $results = DB::select($sql);

        $data['countries'] = $results;
        $data['candidates'] = Concadidates::get();

        return view('sort_countries_form',$data);
    }

    public function saveCountryResult(Request $request){
        Votes::insert([
            'election_center'   =>  $request->post('country'),
            'district'          =>  $request->post('country'),
            'ballot_pen'        =>  1,
            'candidate_id'      =>  $request->post('candidate'),
            'user_id'           =>  Auth::id(),
            'is_country'        =>  1
        ]);

        return array('success'=>true);
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
        ->get();


        return $electors;
    }
}
