<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('m_gedung', function (Blueprint $table) {
            $table->bigIncrements('gedung_id'); // BIGINT, AUTO_INCREMENT
            $table->string('gedung_kode', 10);  // VARCHAR(10)
            $table->string('gedung_nama', 100); // VARCHAR(100)
            $table->string('description', 500);
            $table->timestamps();               // created_at & updated_at (TIMESTAMP)
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_gedung');
    }
};
