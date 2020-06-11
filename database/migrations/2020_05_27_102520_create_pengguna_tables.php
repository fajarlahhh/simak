<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunaTables extends Migration
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
            $table->string('pengguna_nip')->nullable();
            $table->string('pengguna_sandi');
            $table->string('pengguna_hp')->nullable();
            $table->string('pengguna_pangkat')->nullable();
            $table->string('gambar_nama')->nullable();
            $table->string('session_id')->nullable();
            $table->bigInteger('jabatan_id')->unsigned();
            $table->string('notif_id')->nullable();
            $table->string('token');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->primary('pengguna_id');
            $table->foreign('jabatan_id')->references('jabatan_id')->on('jabatan')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('gambar_nama')->references('gambar_nama')->on('gambar')->onDelete('restrict')->onUpdate('cascade');
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
