<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokBahanSeeder extends Seeder
{
    public function run(): void
    {
        $stokBahan = [
            [
                'jenis_bahan' => 'Deterjen',
                'merk' => 'Rinso',
                'stok_tersedia' => 50.5,
                'satuan' => 'kg',
                'harga_beli' => 25000,
                'stok_minimum' => 10,
                'deskripsi' => 'Deterjen bubuk untuk cuci biasa',
            ],
            [
                'jenis_bahan' => 'Deterjen',
                'merk' => 'Attack',
                'stok_tersedia' => 35.0,
                'satuan' => 'kg',
                'harga_beli' => 23000,
                'stok_minimum' => 10,
                'deskripsi' => 'Deterjen bubuk untuk cuci biasa',
            ],
            [
                'jenis_bahan' => 'Deterjen Cair',
                'merk' => 'Molto',
                'stok_tersedia' => 20.0,
                'satuan' => 'liter',
                'harga_beli' => 35000,
                'stok_minimum' => 5,
                'deskripsi' => 'Deterjen cair untuk pakaian sensitif',
            ],
            [
                'jenis_bahan' => 'Pewangi',
                'merk' => 'Downy',
                'stok_tersedia' => 25.5,
                'satuan' => 'liter',
                'harga_beli' => 30000,
                'stok_minimum' => 8,
                'deskripsi' => 'Pewangi pakaian aroma lavender',
            ],
            [
                'jenis_bahan' => 'Pewangi',
                'merk' => 'Soklin Softener',
                'stok_tersedia' => 18.0,
                'satuan' => 'liter',
                'harga_beli' => 28000,
                'stok_minimum' => 8,
                'deskripsi' => 'Pewangi dan pelembut pakaian',
            ],
            [
                'jenis_bahan' => 'Pemutih',
                'merk' => 'Bayclin',
                'stok_tersedia' => 15.0,
                'satuan' => 'liter',
                'harga_beli' => 20000,
                'stok_minimum' => 5,
                'deskripsi' => 'Pemutih untuk pakaian putih',
            ],
            [
                'jenis_bahan' => 'Pengharum Setrika',
                'merk' => 'Stella',
                'stok_tersedia' => 12.0,
                'satuan' => 'liter',
                'harga_beli' => 15000,
                'stok_minimum' => 5,
                'deskripsi' => 'Pengharum untuk saat menyetrika',
            ],
            [
                'jenis_bahan' => 'Dry Cleaning Solution',
                'merk' => 'Perchloroethylene',
                'stok_tersedia' => 30.0,
                'satuan' => 'liter',
                'harga_beli' => 80000,
                'stok_minimum' => 10,
                'deskripsi' => 'Cairan untuk dry cleaning',
            ],
            [
                'jenis_bahan' => 'Plastik Laundry',
                'merk' => 'Generic',
                'stok_tersedia' => 500,
                'satuan' => 'pcs',
                'harga_beli' => 50,
                'stok_minimum' => 100,
                'deskripsi' => 'Plastik kemasan untuk pakaian bersih',
            ],
            [
                'jenis_bahan' => 'Hanger',
                'merk' => 'Plastik',
                'stok_tersedia' => 200,
                'satuan' => 'pcs',
                'harga_beli' => 1000,
                'stok_minimum' => 50,
                'deskripsi' => 'Gantungan baju plastik',
            ],
        ];

        foreach ($stokBahan as $data) {
            DB::table('stok_bahan')->insert($data);
        }

        $this->command->info('✓ StokBahan seeder completed: 10 items created');
    }
}