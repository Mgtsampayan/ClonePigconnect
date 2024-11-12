<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('vaccination_records', function (Blueprint $table) {
            $table->id();
            $table->string('vaccineName');
            $table->unsignedBigInteger('pigId');
            $table->string('vaccineType');
            $table->date('dateAdministered');
            $table->timestamps();

            $table->foreign('pigId')->references('pigId')->on('pigs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vaccination_records');
    }
}