<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisposisiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->bigIncrements('disposisi_id');
            $table->bigInteger('disposisi_id_sebelumnya')->unsigned();
            $table->bigInteger('disposisi_surat_id')->unsigned();
            $table->string('disposisi_jenis_surat');
            $table->string('disposisi_sifat');
            $table->text('disposisi_catatan');
            $table->text('disposisi_proses')->nullable();
            $table->text('disposisi_hasil')->nullable();
            $table->string('operator');
            $table->timestamps();
        });

        Schema::create('disposisi_detail', function (Blueprint $table) {
            $table->bigInteger('disposisi_id')->unsigned();
            $table->bigInteger('jabatan_id')->unsigned();
            $table->tinyInteger('proses')->default(0);
            $table->primary(['disposisi_id', 'jabatan_id']);
            $table->foreign('disposisi_id')->references('disposisi_id')->on('disposisi')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disposisi_detail');
        Schema::dropIfExists('disposisi');
    }
}
