<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLarapollsPollsTable extends Migration
{
    public function up()
    {
        Schema::create('larapolls_polls', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by');
            $table->boolean('allowed');
            $table->string('category');
            $table->string('topic');
            $table->string('info')->nullable();
            $table->boolean('sticky')->default(false);
            $table->boolean('multiple');
            $table->boolean('contra');
            $table->integer('votes')->unsigned()->default(0);
            $table->timestamp('finishes_at')->nullable();
            $table->integer('scale')->unsigned()->default(1);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::drop('larapolls_polls');
    }
}
