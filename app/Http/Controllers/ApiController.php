<?php

namespace App\Http\Controllers;
ini_set('serialize_precision', 16);

use Illuminate\Http\Request;
use App\Models\Electors;
use Illuminate\Support\Facades\DB;
use Response;
class ApiController extends Controller
{

   

    public function voters(Request $request){

        $header = $request->header('Authorization');
        if(!$header || $header != 'gANm5wFB5Z5ljjPPeK0milkOaZUPuVTY'){
            return Response::json(array(
                'code'      =>  401,
                'message'   =>  'Unauthorized'
            ), 401);
        }

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

        $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],2);
        $results['did_not_voted_percentage'] = $this->numberPrecision(100*$results['total_did_not_vote']/$results['total_electors'],2);
        return $results;

    }

    function numberPrecision($number, $decimals = 0)
    {
        $negation = ($number < 0) ? (-1) : 1;
        $coefficient = 10 ** $decimals;
        return $negation * floor((string)(abs($number) * $coefficient)) / $coefficient;
    }

    public function votersByTowns(Request $request){
        $header = $request->header('Authorization');
        if(!$header || $header != 'gANm5wFB5Z5ljjPPeK0milkOaZUPuVTY'){
            return Response::json(array(
                'code'      =>  401,
                'message'   =>  'Unauthorized'
            ), 401);
        }
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

            $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],2);
            $results['did_not_voted_percentage'] = $this->numberPrecision(100*$results['total_did_not_vote']/$results['total_electors'],2);

            $data_by_towns[$town->town] = $results;
        }
        return $data_by_towns;
    }

    public function getVotedByLogDoctrine(Request $request){
        $header = $request->header('Authorization');
        if(!$header || $header != 'gANm5wFB5Z5ljjPPeK0milkOaZUPuVTY'){
            return Response::json(array(
                'code'      =>  401,
                'message'   =>  'Unauthorized'
            ), 401);
        }
        
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

        $results['سني']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='سني' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['سني']['total_electors'] = $data[0]->total;

        $results['سني']['voted_percentage'] = $this->numberPrecision(100*$results['سني']['total_voted']/$results['سني']['total_electors'],2);

        $results['سني']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['سني']['total_voted']/$total_voted,2);

        ///////////
        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='روم ارثوذكس' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        
        $results['روم ارثوذكس']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='روم ارثوذكس' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['روم ارثوذكس']['total_electors'] = $data[0]->total;

        $results['روم ارثوذكس']['voted_percentage'] = $this->numberPrecision(100*$results['روم ارثوذكس']['total_voted']/$results['روم ارثوذكس']['total_electors'],2);
        $results['روم ارثوذكس']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['روم ارثوذكس']['total_voted']/$total_voted,2);


       ///
       $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='ماروني' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        
        $results['ماروني']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='ماروني' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['ماروني']['total_electors'] = $data[0]->total;

        $results['ماروني']['voted_percentage'] = $this->numberPrecision(100*$results['ماروني']['total_voted']/$results['ماروني']['total_electors'],2);
        $results['ماروني']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['ماروني']['total_voted']/$total_voted,2);


        ///
        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='شيعي' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);
        
        $results['شيعي']['total_voted'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='شيعي' ";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['شيعي']['total_electors'] = $data[0]->total;
        $results['شيعي']['voted_percentage'] = $this->numberPrecision(100*$results['شيعي']['total_voted']/$results['شيعي']['total_electors'],2);
        $results['شيعي']['voted_percentage_from_total_voters'] = $this->numberPrecision(100*$results['شيعي']['total_voted']/$total_voted,2);


        return $results;
    }

    public function getVotersByCountries(Request $request){

        $header = $request->header('Authorization');
        if(!$header || $header != 'gANm5wFB5Z5ljjPPeK0milkOaZUPuVTY'){
            return Response::json(array(
                'code'      =>  401,
                'message'   =>  'Unauthorized'
            ), 401);
        }

        $results = array();

        $sql = "SELECT COUNT(*) AS total FROM electors WHERE election_country!=''";

        $data = DB::select($sql);

        $results['total_expatriates'] = $data[0]->total;

        $sql = "SELECT COUNT(*) AS total FROM votes WHERE is_country=1";

        $data = DB::select($sql);

        $results['voted_expatriates'] = $data[0]->total;

        $results['voted_expatriates_percentage'] = $this->numberPrecision(100*$results['voted_expatriates']/$results['total_expatriates'],2);

        $sql = "SELECT COUNT(*) AS total,c.name AS name FROM votes v LEFT JOIN candidates c ON c.id=v.candidate_id WHERE v.is_country=1 GROUP BY c.name";
        $data = DB::select($sql);

        $results['results_for_earch_candidate'] = $data;

        $sql = "SELECT COUNT(*) AS total,election_country AS country FROM electors WHERE election_country!='' AND done=1 GROUP BY election_country";
        $data = DB::select($sql);

        $countries = array();

        foreach($data as $country){
            $sql = "SELECT COUNT(*) AS total FROM electors WHERE election_country='" . $country->country . "'";
            $country_total = DB::select($sql);
            $country_total = $country_total[0]->total;

            $countries[] = array(
                'country'       =>  $country->country,
                'total_voters'  =>  $country_total,
                'total_voted'   =>  $country->total,
                'percentage'    =>  $this->numberPrecision(100*$country->total/$country_total,2)
            );
        }

        $results['results_for_earch_country'] = $countries;

        return $results;

    }
}
