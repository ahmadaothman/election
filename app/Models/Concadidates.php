<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ElectoralLists;
use App\Models\Electors;

class Concadidates extends Model
{
    protected $table = 'candidates';
    protected $fillable = ['name', 'zone','district','log','list_id','note'];


    public function getListAttribute(){
        $list = ElectoralLists::where('id',$this->list_id)->first();
        return $list;
    }

    public function getTotalVotesAttribute(){
        $electors = Electors::where('preferential_vote',$this->id)->get();
        return $electors->count();
    }
}
