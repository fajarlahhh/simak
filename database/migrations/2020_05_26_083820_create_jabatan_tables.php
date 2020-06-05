<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan', function (Blueprint $table) {
            $table->bigIncrements('jabatan_id');
            $table->string('jabatan_nama')->unique();
            $table->string('jabatan_parent')->nullable();
            $table->string('jabatan_silsilah')->nullable();
            $table->tinyInteger('jabatan_pimpinan')->default(0);
            $table->tinyInteger('jabatan_struktural')->default(1);
            $table->tinyInteger('jabatan_verifikator')->default(0);
            $table->bigInteger('bidang_id')->unsigned();
            $table->string('operator');
            $table->timestamps();
            $table->foreign('bidang_id')->references('bidang_id')->on('bidang')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jabatan');
    }
}
