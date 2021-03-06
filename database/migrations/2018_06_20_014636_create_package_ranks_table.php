<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_ranks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rank_id')->unsigned()->nullable();
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('prices');
            $table->integer('month');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_ranks');
    }
}
