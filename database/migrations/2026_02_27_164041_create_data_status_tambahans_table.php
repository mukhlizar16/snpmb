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
        Schema::create('data_status_tambahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_pendaftaran');
            $table->integer('tingkat');
            $table->integer('semester');
            $table->string('kode_status_tambahan')->nullable();
            $table->string('id_jurusan', 20)->nullable();
            $table->string('npsn_sekolah', 20)->nullable();
            $table->string('nisn_siswa')->nullable();

            $table->foreign('nomor_pendaftaran')->references('nomor_pendaftaran')->on('data_siswa')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_status_tambahan');
    }
};
