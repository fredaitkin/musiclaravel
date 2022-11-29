<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlayedFieldToSongs extends Migration
{
    public function up()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->integer('played')->default(0);
        });   
    }


    public function down()
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn('played');
        });
    }
}
