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
            $table->string('word')->unique();
            $table->integer('count');
            $table->boolean('is_word')->nullable();
            $table->boolean('is_acronym')->nullable();
            $table->boolean('is_alphabet')->nullable();
            $table->boolean('is_brand')->nullable();
            $table->boolean('is_capitalized')->nullable();
            $table->boolean('is_country')->nullable();
            $table->boolean('is_day')->nullable();
            $table->boolean('is_honorific')->nullable();
            $table->boolean('is_made_up')->nullable();
            $table->boolean('is_month')->nullable();
            $table->boolean('is_name')->nullable();
            $table->boolean('is_object')->nullable();
            $table->boolean('is_organisation')->nullable();
            $table->boolean('is_place')->nullable();
            $table->boolean('is_religion')->nullable();
            $table->boolean('is_state')->nullable();
            $table->boolean('is_street')->nullable();
            $table->boolean('is_town')->nullable();
            $table->boolean('is_french')->nullable();
            $table->boolean('is_german')->nullable();
            $table->boolean('is_italian')->nullable();
            $table->boolean('is_spanish')->nullable();
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
