<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUndanganTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('undangan', function (Blueprint $table) {
            $table->string('undangan_nomor');
            $table->date('undangan_tanggal');
            $table->string('undangan_sifat')->nullable();
            $table->text('undangan_perihal')->nullable();
            $table->text('undangan_lampiran')->nullable();
            $table->text('undangan_kepada')->nullable();
            $table->longText('undangan_isi')->nullable();
            $table->string('undangan_ttd')->nullable();
            $table->text('undangan_tembusan')->nullable();

            $table->string('jabatan_nama')->nullable();
            $table->text('undangan_pejabat')->nullable();
            $table->text('salam_pembuka')->nullable();
            $table->text('salam_penutup')->nullable();
            $table->text('kop_isi');
            $table->bigInteger('bidang_id')->unsigned();

            $table->tinyInteger('fix')->default(0);
            $table->integer('urutan');
            $table->string('operator');
            $table->timestamps();
            $table->softDeletes();
            $table->primary('undangan_nomor');
            $table->foreign('bidang_id')->references('bidang_id')->on('bidang')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('undangan_lampiran', function (Blueprint $table) {
            $table->string('undangan_nomor');
            $table->string('file');
            $table->foreign('undangan_nomor')->references('undangan_nomor')->on('undangan')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('undangan');
        Schema::dropIfExists('undangan_lampiran');
    }
}
