<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('pengguna_id');
            $table->string('pengguna_nama');
            $table->string('pengguna_sandi');
            $table->string('pengguna_hp')->nullable();
            $table->string('session_id')->nullable();
            $table->bigInteger('jabatan_id');
            $table->rememberToken();
            $table->timestamps();
            $table->primary('pengguna_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengguna');
    }
}
