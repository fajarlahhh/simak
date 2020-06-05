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
            $table->date('edaran_tanggal');
            $table->string('edaran_sifat')->nullable();
            $table->text('edaran_perihal')->nullable();
            $table->text('edaran_lampiran')->nullable();
            $table->text('edaran_kepada')->nullable();
            $table->longText('edaran_isi')->nullable();
            $table->string('edaran_ttd')->nullable();
            $table->text('edaran_tembusan')->nullable();

            $table->string('jabatan_nama')->nullable();
            $table->text('edaran_pejabat')->nullable();
            $table->text('salam_pembuka')->nullable();
            $table->text('salam_penutup')->nullable();
            $table->text('kop_isi');
            $table->bigInteger('bidang_id')->unsigned();

            $table->tinyInteger('fix')->default(0);
            $table->integer('urutan');
            $table->string('operator');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('edaran_nomor');
            $table->foreign('bidang_id')->references('bidang_id')->on('bidang')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('edaran_lampiran', function (Blueprint $table) {
            $table->string('edaran_nomor');
            $table->string('file');
            $table->foreign('edaran_nomor')->references('edaran_nomor')->on('edaran')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('edaran');
        Schema::dropIfExists('edaran_lampiran');
    }
}
