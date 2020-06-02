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
            $table->text('edaran_perihal');
            $table->text('edaran_lampiran');
            $table->text('edaran_kepada');
            $table->longText('edaran_isi');
            $table->string('edaran_ttd');
            $table->text('edaran_tembusan')->nullable();

            $table->string('jabatan_nama');
            $table->text('edaran_pejabat');
            $table->text('salam_pembuka');
            $table->text('salam_penutup');
            $table->text('kop_isi');
            
            $table->tinyInteger('fix')->default(0);
            $table->integer('urutan');
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
