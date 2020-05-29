<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEdaranTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('edaran', function (Blueprint $table) {
            $table->string('edaran_nomor');
            $table->string('edaran_kop');
            $table->string('edaran_tanggal');
            $table->string('edaran_lampiran');
            $table->string('edaran_sifat');
            $table->string('edaran_perihal');
            $table->string('edaran_kepada');
            $table->string('salam_pembuka');
            $table->string('edaran_isi');
            $table->string('salam_penutup');
            $table->string('jabatan_nama');
            $table->string('edaran_ttd_gambar');
            $table->string('edaran_pejabat');
            $table->string('edaran_tembusan');
            $table->string('operator');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('edaran_nomor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edaran_tables');
    }
}
