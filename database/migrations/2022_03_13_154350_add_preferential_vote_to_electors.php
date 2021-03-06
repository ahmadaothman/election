<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreferentialVoteToElectors extends Migration
{
    public function up()
    {
        Schema::table('electors', function (Blueprint $table) {
            $table->string('preferential_vote', 25)->nullable()->default('');
        });
    }

    public function down()
    {
        Schema::table('electors', function (Blueprint $table) {
            Schema::dropIfExists('preferential_vote');
        });
    }
    
}
