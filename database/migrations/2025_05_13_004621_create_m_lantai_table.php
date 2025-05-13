<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('m_lantai', function (Blueprint $table) {
            $table->id('lantai_id');
            $table->string('lantai_nomor'); // Contoh: "Lantai 1", "B1", dst
            $table->text('deskripsi')->nullable(); // Opsional
            $table->unsignedBigInteger('gedung_id');
            $table->foreign('gedung_id')->references('gedung_id')->on('m_gedung')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('m_lantai');
    }
};
