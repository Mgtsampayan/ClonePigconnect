<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pig_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment')->nullable();
            $table->integer('rating')->default(0);
            $table->timestamps();

               });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}