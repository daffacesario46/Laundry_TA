<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cucian_detail', function (Blueprint $table) {
            $table->double('harga_satuan')->nullable()->after('berat_kg');
            $table->double('harga_kiloan')->nullable()->after('harga_satuan');
        });
    }

    public function down(): void
    {
        Schema::table('cucian_detail', function (Blueprint $table) {
            $table->dropColumn(['harga_satuan', 'harga_kiloan']);
        });
    }
};