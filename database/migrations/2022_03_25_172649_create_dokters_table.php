<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoktersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('spesialisasi');
            $table->string('nama');
            $table->string('no_skp')->nullable();
            $table->string('no_sertif_skp')->nullable();
            $table->string('ttd');
            $table->string('alamat');
            $table->string('alamat_praktek')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('dokters');
    }
}
