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
        Schema::create('data_sekolah', function (Blueprint $table) {
            $table->string('npsn', 20)->primary();

            $table->string('nama_sekolah');
            $table->string('jenis_sekolah')->nullable();

            $table->string('kode_kabupaten', 10)->nullable();
            $table->string('nama_kabupaten')->nullable();

            $table->string('kode_provinsi', 10)->nullable();
            $table->string('nama_provinsi')->nullable();

            $table->string('akreditasi_sekolah', 5)->nullable();

            $table->date('tanggal_mulai_akreditasi')->nullable();
            $table->date('tanggal_kadaluwarsa')->nullable();

            $table->integer('nilai_akreditasi')->nullable();

            $table->string('kepemilikan')->nullable();
            $table->string('sumber_data_nilai')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sekolah');
    }
};
