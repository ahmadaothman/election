<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ElectorsImport;
use App\Models\Electors;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
       // dd(Hash::make('Admin@2445!'));
        $count = Electors::count();   
        $count = number_format($count);
        $data = array();
        $data['count'] = $count;
        return view('home',$data);
       
    }

    public function getTotalByDistrict(Request $request){
        $data = DB::select("SELECT district as البلدة,COUNT(*) as المجموع FROM electors GROUP BY district");
        return $data;
    }

    public function getTotalBydoctrine(Request $request){
        $data = DB::select("SELECT log_doctrine as المذهب,COUNT(*) as المجموع FROM electors GROUP BY log_doctrine");
        return $data;
    }

    public function getTotalBySex(Request $request){
        $data = DB::select("SELECT `sex` as الجنس,COUNT(*) as المجموع FROM electors GROUP BY sex");
        return $data;
    }

    public function getTotalByCountry(Request $request){
        $data = DB::select("SELECT `election_country` as الدولة,COUNT(*) as المجموع FROM electors GROUP BY election_country");
        return $data;
    }

    public function getDistrictCenters(Request $request){
        $data = DB::select("SELECT DISTINCT election_center FROM electors WHERE district='" . $request->get('district') . "' AND election_center!=''");
        return $data;

    }
}
