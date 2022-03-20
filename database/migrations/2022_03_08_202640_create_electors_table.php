<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electors', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('fathername', 100);
            $table->string('mothername', 100);
            $table->string('date_of_birth', 32);
            $table->string('sex', 10);
            $table->string('doctrine', 100);
            $table->string('log',25);
            $table->string('log_doctrine', 100);
            $table->string('district', 100);
            $table->string('election_country', 200);
           // $table->string('side', 100)->nullable()->default('text');
            $table->string('telephone', 32)->nullable()->default('text');
            $table->text('note');
            $table->boolean('elected_last_election')->nullable()->default(false);

        });
    }

    public function down()
    {
        Schema::dropIfExists('electors');
    }
}
