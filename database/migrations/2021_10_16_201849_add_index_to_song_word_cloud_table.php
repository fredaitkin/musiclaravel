<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToSongWordCloudTable extends Migration
{

    public function up()
    {
        Schema::table('song_word_cloud', function (Blueprint $table) {
            $table->unique(['word_cloud_id', 'song_id']);
        });

    }

    public function down()
    {
        Schema::table('song_word_cloud', function (Blueprint $table) {
            $table->dropIndex('song_word_cloud_word_cloud_id_song_id_unique');
        });
    }
}
