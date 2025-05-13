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
        Schema::create('m_periode', function (Blueprint $table) {
             $table->year('periode_id')->primary(); // Sesuai dengan kolom year(4)
            $table->string('periode_name', 50);
            $table->tinyInteger('is_active')->default(0); // tinyint(4), default tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_periode');
    }
};
