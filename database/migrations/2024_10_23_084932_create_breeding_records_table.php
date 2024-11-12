<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreedingRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('breeding_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('sow_id');
            $table->unsignedBigInteger('boar_id');
            $table->date('date_of_breeding');
            $table->date('expected_farrowing_date');
            $table->timestamps();

         
        });
    }

    public function down()
    {
        Schema::dropIfExists('breeding_records');
    }
}