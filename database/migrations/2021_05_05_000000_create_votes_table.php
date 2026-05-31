<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{

    public function up()
    {
        Schema::create(config('vote.votes_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(config('vote.user_foreign_key'))->index()->comment('user_id');
            $table->integer('votes');
            $table->morphs('votable');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('vote.votes_table'));
    }
}
