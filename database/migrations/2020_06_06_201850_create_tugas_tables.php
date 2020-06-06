<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->string('tugas_nomor');
            $table->date('tugas_tanggal');
            $table->string('tugas_sifat')->nullable();
            $table->text('tugas_perihal')->nullable();
            $table->text('tugas_lampiran')->nullable();
            $table->text('tugas_kepada')->nullable();
            $table->longText('tugas_isi')->nullable();
            $table->string('tugas_ttd')->nullable();
            $table->text('tugas_tembusan')->nullable();

            $table->string('jabatan_nama')->nullable();
            $table->text('tugas_pejabat')->nullable();
            $table->text('salam_pembuka')->nullable();
            $table->text('salam_penutup')->nullable();
            $table->text('kop_isi');
            $table->bigInteger('bidang_id')->unsigned();

            $table->tinyInteger('fix')->default(0);
            $table->integer('urutan');
            $table->string('operator');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('tugas_nomor');
            $table->foreign('bidang_id')->references('bidang_id')->on('bidang')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('tugas_lampiran', function (Blueprint $table) {
            $table->string('tugas_nomor');
            $table->string('file');
            $table->foreign('tugas_nomor')->references('tugas_nomor')->on('tugas')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('tugas');
        Schema::dropIfExists('tugas_lampiran');
    }
}
