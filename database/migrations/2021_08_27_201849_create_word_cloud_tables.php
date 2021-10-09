<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordCloudTables extends Migration
{

    public function up()
    {
        Schema::create('word_cloud', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('word')->unique()->collation('utf8_bin');
            $table->integer('count');
            $table->boolean('is_word')->nullable();
            $table->string('category')->nullable();
            $table->integer('variant_of')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('song_word_cloud', function (Blueprint $table) {
            $table->integer('word_cloud_id')->unsigned()->index();
            $table->foreign('word_cloud_id')->references('id')->on('word_cloud')->onDelete('cascade');
            $table->integer('song_id')->unsigned()->index();
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('song_word_cloud');
        Schema::dropIfExists('word_cloud');
    }
}
