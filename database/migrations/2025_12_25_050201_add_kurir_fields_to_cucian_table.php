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
            // Tambah kolom untuk tracking kurir
            $table->unsignedBigInteger('staff_jemput_id')->nullable()->after('catatan');
            $table->unsignedBigInteger('kurir_antar_id')->nullable()->after('staff_jemput_id');
            
            // Foreign key constraints
            $table->foreign('staff_jemput_id', 'fk_cucian_staff_jemput')
                  ->references('users_id')
                  ->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
                  
            $table->foreign('kurir_antar_id', 'fk_cucian_kurir_antar')
                  ->references('users_id')
                  ->on('users')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cucian', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign('fk_cucian_staff_jemput');
            $table->dropForeign('fk_cucian_kurir_antar');
            
            // Drop columns
            $table->dropColumn(['staff_jemput_id', 'kurir_antar_id']);
        });
    }
};