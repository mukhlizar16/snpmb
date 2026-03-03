<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_pilihan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_pendaftaran');
            $table->integer('no_urut_pilihan');
            $table->string('kode_prodi');

            $table->foreign('nomor_pendaftaran')->references('nomor_pendaftaran')->on('data_siswa')->cascadeOnDelete();
            $table->foreign('kode_prodi')->references('kode_prodi')->on('data_prodi')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pilihan');
    }
};
