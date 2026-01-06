<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->string('snap_token', 255)->nullable()->after('bukti_bayar');
            $table->string('transaction_id', 255)->nullable()->after('snap_token');
            $table->string('payment_type', 50)->nullable()->after('transaction_id');
            $table->enum('transaction_status', ['pending', 'settlement', 'expire', 'cancel', 'deny'])->nullable()->after('payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'transaction_id', 'payment_type', 'transaction_status']);
        });
    }
};