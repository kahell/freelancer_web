<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned()->nullable();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('bid_id')->unsigned()->nullable();
            $table->foreign('bid_id')->references('id')->on('bids')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('package_rank_id')->unsigned()->nullable();
            $table->foreign('package_rank_id')->references('id')->on('package_ranks')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('method_payments', ['wallet', 'paypal']);
            $table->string('bank_account')->nullable();
            $table->double('salary_amount')->nullable();
            $table->double('tax_amount')->nullable();
            $table->double('total_amount')->nullable();
            $table->datetime('date')->nullable();
            $table->enum('status', ['success', 'pending' ,'failed']);
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
        Schema::dropIfExists('transactions');
    }
}
