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
        Schema::create('m_ruang', function (Blueprint $table) {
            $table->bigIncrements('ruang_id');          // BIGINT AUTO_INCREMENT
            $table->string('ruang_nama', 100);          // VARCHAR(100)
            $table->unsignedBigInteger('lantai_id');    // BIGINT unsigned
            $table->timestamps();                       // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_ruang');
    }
};