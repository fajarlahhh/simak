<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekananTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rekanan', function (Blueprint $table) {
            $table->string('rekanan_nama');
            $table->string('rekanan_lokasi');
            $table->string('operator');
            $table->timestamps();
            $table->primary('rekanan_nama');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rekanan_nama');
    }
}
