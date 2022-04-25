<?php

namespace App\Http\Controllers;

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

        $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],4);
        $results['did_not_voted_percentage'] = $this->numberPrecision(100*$results['total_did_not_vote']/$results['total_electors'],4);
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

            $results['voted_percentage'] = $this->numberPrecision(100*$results['total_voted']/$results['total_electors'],4);
            $results['did_not_voted_percentage'] = $this->numberPrecision(100*$results['total_did_not_vote']/$results['total_electors'],4);

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

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='سني' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['سني'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='روم ارثوذكس' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['روم ارثوذكس'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='ماروني' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['ماروني'] = $data[0]->total;

        $sql = "SELECT COUNT(*) as total FROM electors WHERE log_doctrine='شيعي' AND done=1";

        if($request->get('without_expatriates') && $request->get('without_expatriates') == "1"){
            $sql .= " AND (election_country IS NULL OR election_country = '')";
        }

        $data = DB::select($sql);

        $results['شيعي'] = $data[0]->total;

        return $results;
    }
}
