<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('m_fasilitas', function (Blueprint $table) {
            $table->bigIncrements('fasilitas_id');
            $table->unsignedBigInteger('ruang_id');
            $table->unsignedBigInteger('kategori_id');
            $table->string('fasilitas_kode', 20)->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->timestamps();

            // Foreign key ke m_ruang
            $table->foreign('ruang_id')
                  ->references('ruang_id')
                  ->on('m_ruang')
                  ->onDelete('restrict')  // Mengubah menjadi restrict untuk mencegah penghapusan ruang yang masih memiliki fasilitas
                  ->onUpdate('cascade');
                  
            // Foreign key ke m_kategori (sesuai nama tabel yang Anda punya)
            $table->foreign('kategori_id')
                  ->references('kategori_id')
                  ->on('m_kategori')
                  ->onDelete('restrict')  // Mengubah menjadi restrict untuk mencegah penghapusan kategori yang masih digunakan
                  ->onUpdate('cascade');
        });
    }

    /**
     * Membalikkan migrasi.
     */
    public function down(): void
    {
        Schema::table('m_fasilitas', function (Blueprint $table) {
            $table->dropForeign(['ruang_id']);
            $table->dropForeign(['kategori_id']);
        });
        
        Schema::dropIfExists('m_fasilitas');
    }
};