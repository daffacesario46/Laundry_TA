<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('layanan')->where('layanan_id', 1)->update(['harga' => 5000]);
        DB::table('layanan')->where('layanan_id', 2)->update(['harga' => 6000]);
        DB::table('layanan')->where('layanan_id', 3)->update(['harga' => 7000]);
        DB::table('layanan')->where('layanan_id', 4)->update(['harga' => 4000]);
        DB::table('layanan')->where('layanan_id', 5)->update(['harga' => 10000]);
        DB::table('layanan')->where('layanan_id', 6)->update(['harga' => 15000]);
    }

    public function down()
    {
        DB::table('layanan')->update(['harga' => 0]);
    }
};
