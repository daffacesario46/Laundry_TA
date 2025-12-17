<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        $pembelian = [
            [
                'kode_beli' => 'PB-001',
                'wkt_beli' => Carbon::now()->subDays(30),
                'tanggal_beli' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'jam_beli' => '10:00:00',
                'jenis_bahan' => 'Deterjen',
                'merk' => 'Rinso',
                'jumlah_beli' => 60,
                'total_harga' => 1500000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-002',
                'wkt_beli' => Carbon::now()->subDays(28),
                'tanggal_beli' => Carbon::now()->subDays(28)->format('Y-m-d'),
                'jam_beli' => '11:30:00',
                'jenis_bahan' => 'Deterjen',
                'merk' => 'Attack',
                'jumlah_beli' => 40,
                'total_harga' => 920000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-003',
                'wkt_beli' => Carbon::now()->subDays(25),
                'tanggal_beli' => Carbon::now()->subDays(25)->format('Y-m-d'),
                'jam_beli' => '09:15:00',
                'jenis_bahan' => 'Pewangi',
                'merk' => 'Downy',
                'jumlah_beli' => 30,
                'total_harga' => 900000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-004',
                'wkt_beli' => Carbon::now()->subDays(20),
                'tanggal_beli' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'jam_beli' => '14:00:00',
                'jenis_bahan' => 'Deterjen Cair',
                'merk' => 'Molto',
                'jumlah_beli' => 25,
                'total_harga' => 875000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-005',
                'wkt_beli' => Carbon::now()->subDays(15),
                'tanggal_beli' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'jam_beli' => '10:45:00',
                'jenis_bahan' => 'Pewangi',
                'merk' => 'Soklin Softener',
                'jumlah_beli' => 20,
                'total_harga' => 560000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-006',
                'wkt_beli' => Carbon::now()->subDays(12),
                'tanggal_beli' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'jam_beli' => '13:20:00',
                'jenis_bahan' => 'Pemutih',
                'merk' => 'Bayclin',
                'jumlah_beli' => 20,
                'total_harga' => 400000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-007',
                'wkt_beli' => Carbon::now()->subDays(10),
                'tanggal_beli' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'jam_beli' => '11:00:00',
                'jenis_bahan' => 'Dry Cleaning Solution',
                'merk' => 'Perchloroethylene',
                'jumlah_beli' => 40,
                'total_harga' => 3200000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-008',
                'wkt_beli' => Carbon::now()->subDays(8),
                'tanggal_beli' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'jam_beli' => '15:30:00',
                'jenis_bahan' => 'Plastik Laundry',
                'merk' => 'Generic',
                'jumlah_beli' => 1000,
                'total_harga' => 50000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-009',
                'wkt_beli' => Carbon::now()->subDays(5),
                'tanggal_beli' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'jam_beli' => '09:00:00',
                'jenis_bahan' => 'Hanger',
                'merk' => 'Plastik',
                'jumlah_beli' => 300,
                'total_harga' => 300000,
                'bukti' => null,
            ],
            [
                'kode_beli' => 'PB-010',
                'wkt_beli' => Carbon::now()->subDays(3),
                'tanggal_beli' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'jam_beli' => '12:00:00',
                'jenis_bahan' => 'Pengharum Setrika',
                'merk' => 'Stella',
                'jumlah_beli' => 15,
                'total_harga' => 225000,
                'bukti' => null,
            ],
        ];

        foreach ($pembelian as $data) {
            DB::table('pembelian')->insert($data);
        }

        $this->command->info('✓ Pembelian seeder completed: 10 pembelian created');
    }
}