<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PemakaianSeeder extends Seeder
{
    public function run(): void
    {
        $pemakaian = [
            // Pemakaian dari Pembelian ID 1 (Deterjen Rinso)
            [
                'pembelian_id' => 1,
                'stok_bahan_id' => 1,
                'jumlah_terpakai' => 9.5,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 2 (Deterjen Attack)
            [
                'pembelian_id' => 2,
                'stok_bahan_id' => 2,
                'jumlah_terpakai' => 5.0,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 3 (Pewangi Downy)
            [
                'pembelian_id' => 3,
                'stok_bahan_id' => 4,
                'jumlah_terpakai' => 4.5,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 4 (Deterjen Cair Molto)
            [
                'pembelian_id' => 4,
                'stok_bahan_id' => 3,
                'jumlah_terpakai' => 5.0,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 5 (Pewangi Soklin)
            [
                'pembelian_id' => 5,
                'stok_bahan_id' => 5,
                'jumlah_terpakai' => 2.0,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 6 (Pemutih Bayclin)
            [
                'pembelian_id' => 6,
                'stok_bahan_id' => 6,
                'jumlah_terpakai' => 5.0,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 7 (Dry Cleaning Solution)
            [
                'pembelian_id' => 7,
                'stok_bahan_id' => 8,
                'jumlah_terpakai' => 10.0,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 8 (Plastik Laundry)
            [
                'pembelian_id' => 8,
                'stok_bahan_id' => 9,
                'jumlah_terpakai' => 500,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 9 (Hanger)
            [
                'pembelian_id' => 9,
                'stok_bahan_id' => 10,
                'jumlah_terpakai' => 100,
                'bukti' => null,
            ],
            
            // Pemakaian dari Pembelian ID 10 (Pengharum Setrika)
            [
                'pembelian_id' => 10,
                'stok_bahan_id' => 7,
                'jumlah_terpakai' => 3.0,
                'bukti' => null,
            ],
        ];

        foreach ($pemakaian as $data) {
            DB::table('pemakaian')->insert($data);
        }

        $this->command->info('✓ Pemakaian seeder completed: 10 pemakaian created');
    }
}