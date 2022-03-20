<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Concadidates;
use App\Models\ElectoralLists;

class ConcadidatesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        $data = array();
        $data['concadidates'] = Concadidates::get();

        return view('concadidates',$data);
    }

    public function add(Request $request){
        $data = array();
        $data['heading_title'] = 'اضافة مرشح';
        $data['lists'] = ElectoralLists::get();
        $method = $request->method();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|unique:candidates|max:255',                
            ]);

            if($validated){
                Concadidates::create([
                    'name'      => $request->input('name'),
                    'district'  => $request->input('district') ? $request->input('district') : '',
                    'zone'      => '',
                    'log'       => $request->input('log') ? $request->input('log') : '',
                    'list_id'   => $request->input('list_id') ? $request->input('list_id') : 0,
                    'note'      => $request->input('note') ?  $request->input('note') : '',
                ]);
                return redirect()->route('concadidates_list')->with('status', 'تمت اضافة مرشح جديد!');
            }
        }
       

        return view('concadidate_form',$data);
    }

    public function edit(Request $request, $id){
        $data = array();
        $data['heading_title'] = 'تعديل معلومات المرشح';
        $data['lists'] = ElectoralLists::get();

        $list = Concadidates::where('id',$id)->first();

        $data['name'] = $list->name;
        $data['district'] = $list->district;
        $data['log'] = $list->log;
        $data['note'] = $list->note;
        $data['list_id'] = $list->list_id;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'district' => 'required',
                
            ]);

            if($validated){
                Concadidates::where("id",$id)->update([
                 'name'      => $request->input('name'),
                    'district'  => $request->input('district'),
                    'zone'      => '',
                    'log'       => $request->input('log') ? $request->input('log') : '',
                    'list_id'   => $request->input('list_id'),
                    'note'      => $request->input('note') ?  $request->input('note') : '',
                ]);
                return redirect()->route('concadidates_list')->with('status', 'تم تعديل معلومات المرشح!');
            }
        }

        return view('concadidate_form',$data);
    }

}
