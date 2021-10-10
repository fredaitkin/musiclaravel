<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexToEntriesTable extends Migration
{

    public function up()
    {
        Schema::connection('mysql2')->table('entries', function (Blueprint $table) {
            $table->index('word');
        });

    }

    public function down()
    {
        Schema::connection('mysql2')->table('entries', function (Blueprint $table) {
            $table->dropIndex('word');
        });
    }
}
