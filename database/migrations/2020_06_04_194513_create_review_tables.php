<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review', function (Blueprint $table) {
            $table->string('review_surat_nomor');
            $table->tinyInteger('review_nomor');
            $table->text('review_catatan')->nullable();
            $table->text('review_surat_jenis');
            $table->tinyInteger('fix')->nullable();
            $table->tinyInteger('selesai')->default(0);
            $table->bigInteger('jabatan_id')->unsigned();
            $table->string('operator');
            $table->timestamps();
            $table->primary(['review_nomor_surat', 'review_nomor']);
            $table->foreign('jabatan_id')->references('jabatan_id')->on('jabatan')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review');
    }
}
