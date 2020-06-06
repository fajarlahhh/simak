<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengantarTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
        Schema::create('pengantar', function (Blueprint $table) {
            $table->string('pengantar_nomor');
            $table->date('pengantar_tanggal');
            $table->string('pengantar_sifat')->nullable();
            $table->text('pengantar_perihal')->nullable();
            $table->text('pengantar_lampiran')->nullable();
            $table->text('pengantar_kepada')->nullable();
            $table->longText('pengantar_isi')->nullable();
            $table->string('pengantar_ttd')->nullable();
            $table->text('pengantar_tembusan')->nullable();

            $table->string('jabatan_nama')->nullable();
            $table->text('pengantar_pejabat')->nullable();
            $table->text('salam_pembuka')->nullable();
            $table->text('salam_penutup')->nullable();
            $table->text('kop_isi');
            $table->bigInteger('bidang_id')->unsigned();

            $table->tinyInteger('fix')->default(0);
            $table->integer('urutan');
            $table->string('operator');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('pengantar_nomor');
            $table->foreign('bidang_id')->references('bidang_id')->on('bidang')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('pengantar_lampiran', function (Blueprint $table) {
            $table->string('pengantar_nomor');
            $table->string('file');
            $table->foreign('pengantar_nomor')->references('pengantar_nomor')->on('pengantar')->onDelete('cascade')->onUpdate('cascade');
            $table->primary('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengantar');
        Schema::dropIfExists('pengantar_lampiran');
    }
}
