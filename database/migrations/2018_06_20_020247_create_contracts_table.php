<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bids_id')->unsigned()->nullable();
            $table->foreign('bids_id')->references('id')->on('bids')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['start', 'pending', 'review', 'done']);
            $table->string('file');
            $table->string('link_projects');
            $table->string('description');
            $table->string('date_start');
            $table->string('date_ended');
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
        Schema::dropIfExists('contracts');
    }
}
