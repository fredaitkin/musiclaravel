<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTables extends Migration
{

    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('category')->unique();
            $table->timestamps();
        });

        Schema::create('word_category', function (Blueprint $table) {
            $table->integer('word_cloud_id')->unsigned()->index();
            $table->foreign('word_cloud_id')->references('id')->on('word_cloud')->onDelete('cascade');
            $table->integer('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::dropIfExists('word_category');
        Schema::dropIfExists('category');
    }
}
