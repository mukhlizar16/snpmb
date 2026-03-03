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
        Schema::create('data_portofolio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nomor_pendaftaran');
            $table->string('kode_portofolio');

            $table->foreign('nomor_pendaftaran')->references('nomor_pendaftaran')->on('data_siswa')->cascadeOnDelete();
            $table->foreign('kode_portofolio')->references('kode_portofolio')->on('ref_portofolio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_portofolio');
    }
};
