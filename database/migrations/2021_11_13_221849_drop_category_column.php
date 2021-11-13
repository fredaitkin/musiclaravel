<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCategoryColumn extends Migration
{

    public function up()
    {
        Schema::table('word_cloud', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
}
