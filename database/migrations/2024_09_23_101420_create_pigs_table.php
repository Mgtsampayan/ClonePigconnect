<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePigsTable extends Migration
{
    public function up()
    {
        Schema::create('pigs', function (Blueprint $table) {
            $table->id('pigId');
            $table->unsignedBigInteger('user_id');
            $table->float('weight');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female']);
            $table->string('status');
            $table->timestamps();

                 });
    }

    public function down()
    {
        Schema::dropIfExists('pigs');
    }
}