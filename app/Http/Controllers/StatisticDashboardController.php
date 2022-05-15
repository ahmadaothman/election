<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $data = array();

        return view('statistic_dashboard',$data);
    }

    public function voters(Request $request){

        $results = array();
        //`

        $sql = "SELECT COUNT(*) AS total FROM electors";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " WHERE  election_country IS NULL OR election_country = ''";
        }

        $data = DB::select($sql);
        
        $results['total_electors'] = $data[0]->total;

        $sql = "SELECT COUNT(*) AS total FROM electors WHERE `done`=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) AS total FROM electors WHERE `done`!=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['total_did_not_vote'] = $data[0]->total;

        if((100*$results['total_voted']/$results['total_electors'])>1){
            $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],1);
        }else{
            $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],2);
        }
        $results['did_not_voted_percentage'] = $this->numberPrecision(100*$results['total_did_not_vote']/$results['total_electors'],1);
        return $results;

    }

    public function getVotedByLogDoctrine(Request $request){
       
        
        $results = array();

        $sql = "SELECT COUNT(*) AS total FROM electors WHERE `done`=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $total_voted = $data[0]->total;
        

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='سني' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        $results['سني']['doctrine'] = 'سني';
        $results['سني']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='سني' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['سني']['total_electors'] = $data[0]->total;

        $results['سني']['voted_percentage'] = $this->numberPrecision(100*$results['سني']['total_voted']/$results['سني']['total_electors'],1);

        $results['سني']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['سني']['total_voted']/$total_voted,1);

        ///////////
        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='روم ارثوذكس' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        $results['روم ارثوذكس']['doctrine'] = 'روم ارثوذكس';
        $results['روم ارثوذكس']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='روم ارثوذكس' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['روم ارثوذكس']['total_electors'] = $data[0]->total;

        $results['روم ارثوذكس']['voted_percentage'] = $this->numberPrecision(100*$results['روم ارثوذكس']['total_voted']/$results['روم ارثوذكس']['total_electors'],1);
        $results['روم ارثوذكس']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['روم ارثوذكس']['total_voted']/$total_voted,1);


       ///
       $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='ماروني' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        $results['ماروني']['doctrine'] = 'ماروني';
        $results['ماروني']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='ماروني' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['ماروني']['total_electors'] = $data[0]->total;

        $results['ماروني']['voted_percentage'] = $this->numberPrecision(100*$results['ماروني']['total_voted']/$results['ماروني']['total_electors'],1);
        $results['ماروني']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['ماروني']['total_voted']/$total_voted,1);


        ///
        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='شيعي' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        $results['شيعي']['doctrine'] = 'شيعي';
        $results['شيعي']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='شيعي' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['شيعي']['total_electors'] = $data[0]->total;
        $results['شيعي']['voted_percentage'] = $this->numberPrecision(100*$results['شيعي']['total_voted']/$results['شيعي']['total_electors'],1);
        $results['شيعي']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['شيعي']['total_voted']/$total_voted,1);

        $data = array();
        $data[] =  $results['سني'];
        $data[] =  $results['روم ارثوذكس'];
        $data[] =  $results['ماروني'];
        $data[] =  $results['شيعي'];
        return $data;
    }

    function numberPrecision($number, $decimals = 0)
    {
        $negation = ($number < 0) ? (-1) : 1;
        $coefficient = 10 ** $decimals;
        return $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
    }

    public function votersByTowns(Request $request){
        $sql = "SELECT COUNT(*) AS total FROM electors WHERE `done`=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $total_voted = $data[0]->total;

        $sql = "SELECT DISTINCT (district) AS town FROM electors";
        $data = DB::select($sql);

        $data_by_towns = array();
        
        foreach($data as $town){
            $results = array();

            $sql = "SELECT COUNT(*) AS total FROM electors WHERE district='" . $town->town . "'";

            if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
                $sql .= " AND  (election_country IS NULL OR election_country = '')";
            }

            $data = DB::select($sql);
            
            $results['total_electors'] = $data[0]->total;

            $sql = "SELECT COUNT(*) AS total FROM electors WHERE `done`=1 AND district='" . $town->town . "'";

            if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
                $sql .= " AND (election_country IS NULL OR election_country = '')";
            }

            $data = DB::select($sql);

            $results['total_voted'] = $data[0]->total;

            $sql = "SELECT COUNT(*) AS total FROM electors WHERE `done`!=1 AND district='" . $town->town . "'";

            if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
                $sql .= " AND (election_country IS NULL OR election_country = '')";
            }

            $data = DB::select($sql);

            $results['total_did_not_vote'] = $data[0]->total;

            $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],1);
            $results['did_not_voted_percentage'] = $this->numberPrecision(100*$results['total_did_not_vote']/$results['total_electors'],1);

            $results['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['total_voted']/$total_voted,1);
            $results['town'] = $town->town;
            $data_by_towns[] = $results;
        }

        return $data_by_towns;
    }

    public function getDataBySex(Request $request){
        $sql = "SELECT COUNT(*) as total,sex FROM electors WHERE done=1 GROUP BY sex";
        $data = DB::select($sql);
        return $data;
    }

    public function results(Request $request){
        $data = array();
        $sql = "SELECT COUNT(*) as count FROM votes WHERE is_country=0";
        $data = DB::select($sql);

        $results = array();
        $results['total_votes'] = $data[0]->count;

        $sql = "SELECT COUNT(*) as count FROM votes WHERE is_country=0 AND candidate_id=11";
        $data = DB::select($sql);
        $results['total_sami'] = number_format($data[0]->count);

        return View('results',$results);
    }

    public function resultsApi(Request $request){
        $data = array();

        $sql = "SELECT COUNT(*) as count FROM votes WHERE is_country=0";
        $data = DB::select($sql);

        $results = array();
        $results['total_votes'] = number_format($data[0]->count);

        $sql = "SELECT COUNT(*) as count FROM votes WHERE is_country=0 AND candidate_id=11";
        $data = DB::select($sql);
        $results['total_sami'] = number_format($data[0]->count);

        //$results['total_sami_percantage'] = number_format(100*$results['total_sami']/$results['total_votes']);

        $sql = "SELECT district,COUNT(*) as count FROM votes WHERE is_country=0 AND candidate_id=11 GROUP BY district";

        $data = DB::select($sql);
        $results['sami_district_total'] = $data;


        return $results;
    }

    public function resultsApiDataForEachConcadidate(Request $request){
        $sql = "SELECT c.name as name,COUNT(*) as count FROM votes v LEFT JOIN candidates c ON c.id=v.candidate_id GROUP BY c.name";
        $data = DB::select($sql);
        
        return $data;
    }

}
