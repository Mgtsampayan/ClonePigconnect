<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToPigsTable extends Migration
{
    public function up()
    {
        Schema::table('pigs', function (Blueprint $table) {
            $table->string('image')->after('status');
        });
    }

    public function down()
    {
        Schema::table('pigs', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}