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
            // Tambah kolom jenis_cucian setelah layanan_id
            $table->enum('jenis_cucian', ['kiloan', 'satuan'])
                  ->after('layanan_id')
                  ->nullable()
                  ->comment('Jenis cucian: kiloan atau satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cucian', function (Blueprint $table) {
            $table->dropColumn('jenis_cucian');
        });
    }
};