<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePigWeightsTable extends Migration
{
    public function up()
    {
        Schema::create('pig_weights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pig_id');
            $table->date('date');
            $table->float('weight');
            $table->timestamps();

            $table->foreign('pig_id')->references('PigId')->on('pigs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pig_weights');
    }
}