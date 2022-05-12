<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Carbon;
use User;
use Auth;
use Session;
use Illuminate\Support\Facades\DB;

class LoginSuccessful
{
   
    public function __construct()
    {
        //
    }


    public function handle($event)
    {
        $user = Auth::user();

        $userId = $user->id;
        $mytime = Carbon\Carbon::now();

        DB::insert("INSERT INTO user_login_log SET user_id='" . $userId . "',ip='" . $this->getUserIpAddr() . "',user_agent='" . $_SERVER['HTTP_USER_AGENT'] . "',created_at='" . $mytime->toDateTimeString() . "',updated_at='" . $mytime->toDateTimeString() . "'");
        echo $userId;
    }

    public function getUserIpAddr(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';    
        return $ipaddress;
     }
}
