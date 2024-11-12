<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmInformationTable extends Migration
{
    public function up()
    {
        Schema::create('farm_information', function (Blueprint $table) {
            $table->id();
            $table->string('feeding_type');
            $table->string('frequency_of_feeding');
            $table->decimal('min_price_per_kilo', 8, 2);
            $table->decimal('max_price_per_kilo', 8, 2);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('farm_information');
    }
}