<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Electors;
use App\Models\Concadidates;

class ElectorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('electors');
    }

    public function get()
    {
        return Electors::get();
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
}
