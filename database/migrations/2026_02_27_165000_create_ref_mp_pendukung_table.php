<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_mp_pendukung', function (Blueprint $table) {
            $table->string('kode_prodi');
            $table->string('id_jurusan', 20);
            $table->string('kode_mata_pelajaran');

            $table->primary(['kode_prodi', 'id_jurusan', 'kode_mata_pelajaran'], 'ref_mp_pendukung_pk');

            $table->foreign('kode_prodi')->references('kode_prodi')->on('data_prodi')->cascadeOnDelete();
            $table->foreign('id_jurusan')->references('id_jurusan')->on('ref_jurusan')->cascadeOnDelete();
            $table->foreign('kode_mata_pelajaran')->references('kode_mata_pelajaran')->on('ref_mata_pelajaran')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_mp_pendukung');
    }
};
