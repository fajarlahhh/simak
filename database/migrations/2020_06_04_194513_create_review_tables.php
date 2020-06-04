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
            $table->string('review_nomor_surat');
            $table->tinyInteger('review_nomor');
            $table->text('review_catatan')->nullable();
            $table->text('review_jenis_surat');
            $table->tinyInteger('fix')->nullable();
            $table->tinyInteger('selesai')->default(0);
            $table->string('verifikator');
            $table->string('operator');
            $table->timestamps();
            $table->primary(['review_nomor_surat', 'review_nomor']);
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
