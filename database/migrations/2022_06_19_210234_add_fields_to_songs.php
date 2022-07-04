<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSongs extends Migration
{
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->boolean('do_not_play')->nullable();
            $table->date('last_played')->nullable();
            $table->smallInteger('rank')->nullable();
        });   
    }


    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('do_not_play');
            $table->dropColumn('last_played');
            $table->dropColumn('rank');
        });
    }
}
