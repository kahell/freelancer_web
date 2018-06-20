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
            $table->integer('bid_id')->unsigned()->nullable();
            $table->foreign('bid_id')->references('id')->on('bids')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['start', 'pending', 'review', 'done']);
            $table->string('file')->nullable();
            $table->string('link_projects')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_ended')->nullable();
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
