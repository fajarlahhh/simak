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
            $table->string('jabatan_nama');
            $table->string('jabatan_parent')->nullable();
            $table->string('jabatan_silsilah')->nullable();
            $table->tinyInteger('jabatan_pimpinan')->default(0);
            $table->tinyInteger('jabatan_struktural')->default(1);
            $table->timestamps();
            $table->primary('jabatan_nama');
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
