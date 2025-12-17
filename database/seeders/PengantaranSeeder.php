<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengantaranSeeder extends Seeder
{
    public function run(): void
    {
        $pengantaran = [
            // Pengantaran untuk order yang sudah diantar (selesai)
            [
                'cucian_id' => 1,
                'kurir_id' => 4, // Joko Widodo (kurir)
                'alamat_antar' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'status' => 'selesai',
                'tgl_berangkat' => Carbon::now()->subDays(6),
                'foto' => null,
                'catatan' => 'Cucian sudah diterima pelanggan',
            ],
            
            // Pengantaran untuk order yang sedang diantar (diproses)
            [
                'cucian_id' => 3,
                'kurir_id' => 5, // Ahmad Dahlan (kurir)
                'alamat_antar' => 'Jl. Thamrin No. 5, Jakarta Pusat',
                'status' => 'diproses',
                'tgl_berangkat' => Carbon::now()->subHours(2),
                'foto' => null,
                'catatan' => 'Kurir sedang dalam perjalanan',
            ],
            
            // Pengantaran untuk order yang menunggu diantar (menunggu)
            [
                'cucian_id' => 5,
                'kurir_id' => null,
                'alamat_antar' => 'Jl. Kuningan No. 20, Jakarta Selatan',
                'status' => 'menunggu',
                'tgl_berangkat' => null,
                'foto' => null,
                'catatan' => 'Menunggu kurir tersedia',
            ],
        ];

        foreach ($pengantaran as $data) {
            DB::table('pengantaran')->insert($data);
        }

        $this->command->info('✓ Pengantaran seeder completed: 3 pengantaran created (1 selesai, 1 diproses, 1 menunggu)');
    }
}