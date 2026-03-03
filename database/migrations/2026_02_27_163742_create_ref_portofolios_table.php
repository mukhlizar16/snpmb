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
        Schema::create('ref_portofolio', function (Blueprint $table) {
            $table->string('kode_portofolio')->primary();
            $table->string('jenis_portofolio')->nullable();
            $table->string('nama_portofolio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ref_portofolio');
    }
};
