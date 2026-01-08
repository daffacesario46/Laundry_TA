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
        Schema::table('cucian', function (Blueprint $table) {
            // Tambahkan kolom metode_cuci setelah kolom jenis_cucian
            $table->enum('metode_cuci', ['normal', 'express'])
                  ->default('normal')
                  ->after('jenis_cucian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cucian', function (Blueprint $table) {
            // Hapus kolom metode_cuci jika rollback
            $table->dropColumn('metode_cuci');
        });
    }
};
