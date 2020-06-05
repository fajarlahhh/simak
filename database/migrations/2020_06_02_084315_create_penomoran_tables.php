<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenomoranTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penomoran', function (Blueprint $table) {
            $table->string('penomoran_jenis');
            $table->string('penomoran_format');
            $table->string('operator');
            $table->timestamps();
            $table->primary('penomoran_jenis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penomoran');
    }
}
