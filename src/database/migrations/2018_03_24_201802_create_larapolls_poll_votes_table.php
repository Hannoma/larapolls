<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLarapollsPollVotesTable extends Migration
{
    public function up()
    {
        Schema::create('larapolls_poll_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('poll_option_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('pro');
            $table->foreign('user_id')
              ->references('id')->on('users')
              ->onDelete('cascade');
            $table->foreign('poll_option_id')
              ->references('id')->on(config('larapolls.database_table_prefix').'_poll_options')
              ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::drop('larapolls_poll_votes');
    }
}
