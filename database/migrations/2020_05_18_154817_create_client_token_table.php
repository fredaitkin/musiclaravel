<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTokenTable extends Migration
{

    public function up()
    {
        Schema::create('client_token', function (Blueprint $table) {
            $table->bigIncrements('client_id');
            $table->string('client', 128)->unique();
            $table->string('token', 128)->unique();
            $table->dateTime('expires')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_token');
    }
}
