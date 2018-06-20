<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('gender', ['male', 'female']);
            $table->string('bod')->nullable();
            $table->string('avatar')->nullable();
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->double('wallet')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('skills')->nullable();
            $table->text('curicullum_vitae')->nullable();
            $table->double('salary')->nullable();
            $table->integer('points')->nullable();
            $table->integer('rank_id')->unsigned()->nullable();
            $table->foreign('rank_id')->references('id')->on('ranks')->onDelete('set null')->onUpdate('cascade');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
