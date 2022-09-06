<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriksasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periksas', function (Blueprint $table) {
            $table->id();
            $table->string('no_lab')->unique();
            $table->foreignId('pasien_id');
            $table->foreignId('dokter_id')->nullable();
            $table->string('asal_ruangan')->nullable();
            $table->string('metode_bayar')->nullable();
            $table->string('no_sep')->nullable();
            $table->boolean('home_service');
            $table->smallinteger('is_done');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periksas');
    }
}
