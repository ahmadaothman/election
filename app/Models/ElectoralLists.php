<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concadidates;


class ElectoralLists extends Model
{
    protected $table = 'electoral_lists';
    protected $fillable = ['name', 'side'];
    public function getConcadidatesCountAttribute(){
        $count = Concadidates::where('list_id',$this->id)->get();
        return $count->count();
    }

    public function getTotalVotesAttribute(){
        $concadidates = Concadidates::where('list_id',$this->id)->get();
        $total = 0;
        foreach($concadidates as $item){
            $total = $total + (int)$item->TotalVotes;
        }
        return $total;
    }
}
