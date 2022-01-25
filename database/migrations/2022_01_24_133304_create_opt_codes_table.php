<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptCodesTable extends Migration
{

    public function up()
    {
        Schema::create('opt_codes', function (Blueprint $table) {
            $table->id();
            $table->string('opt')->nullable();
            $table->string('mobile')->unique();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('opt_codes');
    }
}
