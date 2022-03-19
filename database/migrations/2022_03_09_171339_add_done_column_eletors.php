<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoneColumnEletors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('electors', function (Blueprint $table) {
            $table->boolean('done')->nullable()->default(false);
        });
    }

    public function down()
    {
        Schema::table('electors', function (Blueprint $table) {
            $table->dropColumn('done');
        });
    }
}
