<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisi', function (Blueprint $table) {
            $table->string('nomor_surat');
            $table->tinyInteger('revisi_nomor');
            $table->text('revisi_catatan')->nullable();
            $table->text('revisi_jenis_surat');
            $table->string('revisi_editor');
            $table->string('operator');
            $table->timestamps();
            $table->primary(['nomor_surat', 'revisi_nomor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisi');
    }
}
