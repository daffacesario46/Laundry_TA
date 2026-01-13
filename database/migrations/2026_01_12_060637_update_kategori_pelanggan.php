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
        // Update data yang ada: member -> online, reguler -> offline
        DB::table('pelanggan')
            ->where('kategori_pelanggan', 'member')
            ->update(['kategori_pelanggan' => 'online']);
            
        DB::table('pelanggan')
            ->where('kategori_pelanggan', 'reguler')
            ->update(['kategori_pelanggan' => 'offline']);
        
        // Ubah enum kolom
        DB::statement("ALTER TABLE pelanggan MODIFY kategori_pelanggan ENUM('online', 'offline') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama
        DB::statement("ALTER TABLE pelanggan MODIFY kategori_pelanggan ENUM('member', 'reguler') NOT NULL");
        
        // Kembalikan data: online -> member, offline -> reguler
        DB::table('pelanggan')
            ->where('kategori_pelanggan', 'online')
            ->update(['kategori_pelanggan' => 'member']);
            
        DB::table('pelanggan')
            ->where('kategori_pelanggan', 'offline')
            ->update(['kategori_pelanggan' => 'reguler']);
    }
};