<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concadidates;

class Electors extends Model
{

    protected $table = 'electors';
    protected $guarded = [];
    public $timestamps = false;
   protected $appends = array('FullName');

   public function getFullNameAttribute(){
        return $this->firstname . " " . $this->fathername . " " . $this->lastname;  
    }

   /* public function getSideDetailsAttribute(){
        if($this->preferential_vote == 'none'){
            return 'غير معروف';
        }else if($this->preferential_vote == 'will_no'){
            return 'لن يقترع';
        }else if(empty($this->preferential_vote)){
            return '';
        }else{
            $concadidates = Concadidates::where('id',$this->preferential_vote)->first();
            if($concadidates){
                try {
                    return $concadidates->name != null ? $concadidates->name : '';
                 } catch (Throwable $e) {
              
                     return '';
                 }
            }
            
        }
    }*/
}
