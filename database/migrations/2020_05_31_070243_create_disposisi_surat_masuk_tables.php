<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposisiSuratMasukTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisi_surat_masuk', function (Blueprint $table) {
            $table->bigIncrements('disposisi_surat_masuk_id');
            $table->string('surat_masuk_nomor');
            $table->string('disposisi_surat_masuk_sifat');
            $table->text('disposisi_surat_masuk_catatan');
            $table->string('disposisi_surat_masuk_proses');
            $table->string('disposisi_surat_masuk_hasil');
            $table->string('operator');
            $table->timestamps();
            $table->foreign('surat_masuk_nomor')->references('surat_masuk_nomor')->on('surat_masuk')->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('disposisi_surat_masuk_detail', function (Blueprint $table) {
            $table->bigInteger('disposisi_surat_masuk_id')->unsigned();
            $table->tinyInteger('disposisi_surat_masuk_detail_proses');
            $table->string('jabatan_nama');
            $table->foreign('disposisi_surat_masuk_id')->references('disposisi_surat_masuk_id')->on('disposisi_surat_masuk')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposisi_surat_masuk');
        Schema::dropIfExists('disposisi_surat_masuk_detail');
    }
}
