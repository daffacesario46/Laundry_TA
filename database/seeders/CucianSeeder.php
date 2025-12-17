<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CucianSeeder extends Seeder
{
    public function run(): void
    {
        $cucian = [
            // Order Online dari Member
            [
                'pelanggan_id' => 1, // Andi Wijaya (member online)
                'layanan_id' => 2, // Cuci Setrika
                'jenis_order' => 'online',
                'jenis_ambil' => 'diantar',
                'tgl_order' => Carbon::now()->subDays(10),
                'estimasi' => Carbon::now()->subDays(7),
                'tgl_selesai' => Carbon::now()->subDays(7),
                'tgl_diambil' => Carbon::now()->subDays(6),
                'total_item' => 1,
                'total_berat' => 5.0,
                'total_harga' => 35000,
                'status_cucian' => 'diambil',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 2, // Dewi Lestari (member online)
                'layanan_id' => 3, // Cuci Setrika Express
                'jenis_order' => 'online',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now()->subDays(8),
                'estimasi' => Carbon::now()->subDays(7),
                'tgl_selesai' => Carbon::now()->subDays(7),
                'tgl_diambil' => Carbon::now()->subDays(7),
                'total_item' => 1,
                'total_berat' => 3.5,
                'total_harga' => 35000,
                'status_cucian' => 'diambil',
                'catatan' => 'Butuh cepat untuk acara kantor',
            ],
            [
                'pelanggan_id' => 3, // Rudi Hartono (member online)
                'layanan_id' => 5, // Cuci Satuan Premium
                'jenis_order' => 'online',
                'jenis_ambil' => 'diantar',
                'tgl_order' => Carbon::now()->subDays(5),
                'estimasi' => Carbon::now()->subDays(2),
                'tgl_selesai' => Carbon::now()->subDays(2),
                'tgl_diambil' => null,
                'total_item' => 4,
                'total_berat' => null,
                'total_harga' => 54000,
                'status_cucian' => 'selesai',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 4, // Sari Indah (member online)
                'layanan_id' => 1, // Cuci Kering
                'jenis_order' => 'online',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now()->subDays(3),
                'estimasi' => Carbon::now()->subDays(1),
                'tgl_selesai' => null,
                'tgl_diambil' => null,
                'total_item' => 1,
                'total_berat' => 4.0,
                'total_harga' => 20000,
                'status_cucian' => 'diproses',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 5, // Bambang Sutopo (member online)
                'layanan_id' => 6, // Dry Cleaning
                'jenis_order' => 'online',
                'jenis_ambil' => 'diantar',
                'tgl_order' => Carbon::now()->subDays(2),
                'estimasi' => Carbon::now()->addDays(2),
                'tgl_selesai' => null,
                'tgl_diambil' => null,
                'total_item' => 2,
                'total_berat' => null,
                'total_harga' => 40000,
                'status_cucian' => 'diproses',
                'catatan' => 'Jas untuk meeting penting',
            ],
            
            // Order Offline dari Walk-in Customer
            [
                'pelanggan_id' => 6, // Ibu Susi (reguler offline)
                'layanan_id' => 2, // Cuci Setrika
                'jenis_order' => 'offline',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now()->subDays(12),
                'estimasi' => Carbon::now()->subDays(9),
                'tgl_selesai' => Carbon::now()->subDays(9),
                'tgl_diambil' => Carbon::now()->subDays(8),
                'total_item' => 1,
                'total_berat' => 6.0,
                'total_harga' => 42000,
                'status_cucian' => 'diambil',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 7, // Pak Agus (reguler offline)
                'layanan_id' => 4, // Setrika Saja
                'jenis_order' => 'offline',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now()->subDays(6),
                'estimasi' => Carbon::now()->subDays(5),
                'tgl_selesai' => Carbon::now()->subDays(5),
                'tgl_diambil' => Carbon::now()->subDays(4),
                'total_item' => 1,
                'total_berat' => 3.0,
                'total_harga' => 12000,
                'status_cucian' => 'diambil',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 8, // Ibu Ratna (reguler offline)
                'layanan_id' => 5, // Cuci Satuan Premium
                'jenis_order' => 'offline',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now()->subDays(4),
                'estimasi' => Carbon::now()->subDays(1),
                'tgl_selesai' => Carbon::now()->subDays(1),
                'tgl_diambil' => null,
                'total_item' => 3,
                'total_berat' => null,
                'total_harga' => 57000,
                'status_cucian' => 'selesai',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 9, // Pak Dedi (reguler offline)
                'layanan_id' => 1, // Cuci Kering
                'jenis_order' => 'offline',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now()->subDays(1),
                'estimasi' => Carbon::now()->addDays(1),
                'tgl_selesai' => null,
                'tgl_diambil' => null,
                'total_item' => 1,
                'total_berat' => 7.5,
                'total_harga' => 37500,
                'status_cucian' => 'diproses',
                'catatan' => null,
            ],
            [
                'pelanggan_id' => 10, // Ibu Lina (reguler offline)
                'layanan_id' => 2, // Cuci Setrika
                'jenis_order' => 'offline',
                'jenis_ambil' => 'ambil_sendiri',
                'tgl_order' => Carbon::now(),
                'estimasi' => Carbon::now()->addDays(3),
                'tgl_selesai' => null,
                'tgl_diambil' => null,
                'total_item' => 1,
                'total_berat' => 2.5,
                'total_harga' => 17500,
                'status_cucian' => 'menunggu',
                'catatan' => null,
            ],
        ];

        foreach ($cucian as $data) {
            DB::table('cucian')->insert($data);
        }

        $this->command->info('✓ Cucian seeder completed: 10 cucian created (5 online, 5 offline)');
    }
}