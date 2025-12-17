<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PenjemputanSeeder extends Seeder
{
    public function run(): void
    {
        $penjemputan = [
            // Penjemputan untuk order online yang sudah selesai dijemput
            [
                'cucian_id' => 1,
                'staff_id' => 2, // Budi Santoso (staff)
                'alamat_jemput' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'status' => 'selesai',
                'tgl_order' => Carbon::now()->subDays(10),
                'foto' => null,
                'catatan' => 'Cucian sudah dijemput tepat waktu',
            ],
            [
                'cucian_id' => 2,
                'staff_id' => 3, // Siti Aminah (staff)
                'alamat_jemput' => 'Jl. Sudirman No. 25, Jakarta Pusat',
                'status' => 'selesai',
                'tgl_order' => Carbon::now()->subDays(8),
                'foto' => null,
                'catatan' => 'Pelanggan request express',
            ],
            [
                'cucian_id' => 3,
                'staff_id' => 2, // Budi Santoso (staff)
                'alamat_jemput' => 'Jl. Thamrin No. 5, Jakarta Pusat',
                'status' => 'selesai',
                'tgl_order' => Carbon::now()->subDays(5),
                'foto' => null,
                'catatan' => null,
            ],
            
            // Penjemputan untuk order online yang sedang dijemput
            [
                'cucian_id' => 4,
                'staff_id' => 3, // Siti Aminah (staff)
                'alamat_jemput' => 'Jl. Gatot Subroto No. 15, Jakarta Selatan',
                'status' => 'diproses',
                'tgl_order' => Carbon::now()->subDays(3),
                'foto' => null,
                'catatan' => 'Staff sedang dalam perjalanan',
            ],
            
            // Penjemputan untuk order online yang menunggu dijemput
            [
                'cucian_id' => 5,
                'staff_id' => null,
                'alamat_jemput' => 'Jl. Kuningan No. 20, Jakarta Selatan',
                'status' => 'menunggu',
                'tgl_order' => Carbon::now()->subDays(2),
                'foto' => null,
                'catatan' => 'Menunggu staff tersedia',
            ],
        ];

        foreach ($penjemputan as $data) {
            DB::table('penjemputan')->insert($data);
        }

        $this->command->info('✓ Penjemputan seeder completed: 5 penjemputan created (3 selesai, 1 diproses, 1 menunggu)');
    }
}