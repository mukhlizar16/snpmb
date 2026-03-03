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
        Schema::create('data_prestasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_pendaftaran');
            $table->integer('no_prestasi');
            $table->string('jenis_prestasi')->nullable();
            $table->string('jenjang_prestasi')->nullable();
            $table->string('individu_berkelompok')->nullable();
            $table->string('lembaga_pemberi_prestasi')->nullable();
            $table->year('tahun')->nullable();
            $table->decimal('nilai_prestasi', 8, 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('file_sertifikat')->nullable();
            $table->timestamps();

            $table->foreign('nomor_pendaftaran')->references('nomor_pendaftaran')->on('data_siswa')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_prestasi');
    }
};
