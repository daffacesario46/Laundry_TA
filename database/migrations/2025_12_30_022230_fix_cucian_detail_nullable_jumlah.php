<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ✅ Ubah kolom cucian_detail agar support kiloan & satuan
        Schema::table('cucian_detail', function (Blueprint $table) {
            // PENTING: Kolom jumlah jadi NULLABLE (untuk kiloan)
            $table->integer('jumlah')->nullable()->default(null)->change();
            
            // Kolom berat_kg sudah nullable (OK)
            // Kolom harga_satuan & harga_kiloan sudah nullable (OK)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cucian_detail', function (Blueprint $table) {
            // Kembalikan ke NOT NULL dengan default 1
            $table->integer('jumlah')->nullable(false)->default(1)->change();
        });
    }
};