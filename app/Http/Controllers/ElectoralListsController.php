<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ElectoralLists;

class ElectoralListsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('mobile_user');
        $this->middleware('statistics');
    }
    
    public function index(Request $request){
        $data = array();
        $data['lists'] = ElectoralLists::get();

        return view('electoral_list',$data);
    }

    public function add(Request $request){
        $data = array();
        $data['heading_title'] = 'اضافة لائحة';

        $method = $request->method();

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|unique:electoral_lists|max:255',
                'side' => 'required',
            ]);

            if($validated){
                ElectoralLists::create([
                    'name'  => $request->input('name'),
                    'side'  => $request->input('side')
                ]);
                return redirect()->route('electoral_lists')->with('status', 'تمت اضافة لائحة جديدة!');
            }
        }
       

        return view('electoral_list_form',$data);
    }

    public function edit(Request $request, $id){
        $data = array();
        $data['heading_title'] = 'تعديل اللائحة';
        $list = ElectoralLists::where('id',$id)->first();
        $data['name'] = $list->name;
        $data['side'] = $list->side;

        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'side' => 'required',
            ]);

            if($validated){
                ElectoralLists::where("id",$id)->update([
                    'name'  => $request->input('name'),
                    'side'  => $request->input('side')
                ]);
                return redirect()->route('electoral_lists')->with('status', 'تم تعديل معلومات اللائحة!');
            }
        }

        return view('electoral_list_form',$data);
        //$this->form($request);
    }

   
}
