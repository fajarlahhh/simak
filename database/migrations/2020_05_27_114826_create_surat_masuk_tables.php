<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratMasukTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->string('surat_masuk_nomor');
            $table->string('surat_masuk_tanggal_masuk');
            $table->string('surat_masuk_tanggal_surat');
            $table->string('surat_masuk_perihal');
            $table->string('surat_masuk_asal');
            $table->string('surat_masuk_keterangan');
            $table->string('surat_masuk_file');
            $table->string('kirim');
            $table->string('operator');
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
        Schema::dropIfExists('surat_masuk');
    }
}
