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
            $table->bigIncrements('surat_masuk_id');
            $table->string('surat_masuk_nomor');
            $table->date('surat_masuk_tanggal_masuk');
            $table->date('surat_masuk_tanggal_surat');
            $table->text('surat_masuk_perihal');
            $table->string('surat_masuk_asal');
            $table->longText('surat_masuk_keterangan');
            $table->string('file');
            $table->tinyInteger('disposisi')->default(0);
            $table->string('operator');
            $table->timestamps();
            $table->softDeletes();
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
