<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLarapollsPollOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('larapolls_poll_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('poll_id')->unsigned();
            $table->string('option');
            $table->foreign('poll_id')
              ->references('id')->on(config('larapolls.database_table_prefix').'_polls')
              ->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::drop('larapolls_poll_options');
    }
}
