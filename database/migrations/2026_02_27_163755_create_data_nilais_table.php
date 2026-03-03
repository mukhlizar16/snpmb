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
        Schema::create('data_nilai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_pendaftaran');
            $table->integer('semester');
            $table->string('kode_mata_pelajaran');
            $table->boolean('wajib')->default(false);
            $table->integer('tingkat')->nullable();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->decimal('nilai_validasi_tka', 5, 2)->nullable();
            $table->integer('tahun_kur')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('kkm', 5, 2)->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->timestamps();

            $table->foreign('nomor_pendaftaran')->references('nomor_pendaftaran')->on('data_siswa')->cascadeOnDelete();
            $table->foreign('kode_mata_pelajaran')->references('kode_mata_pelajaran')->on('ref_mata_pelajaran');

            $table->unique(['nomor_pendaftaran', 'semester', 'kode_mata_pelajaran']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_nilai');
    }
};
