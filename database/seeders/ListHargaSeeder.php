<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListHargaSeeder extends Seeder
{
    public function run(): void
    {
        $listHarga = [
            // Layanan Kiloan
            [
                'nama_item' => 'Cuci Kering (per kg)',
                'harga_satuan' => 0,
                'harga_kiloan' => 5000,
            ],
            [
                'nama_item' => 'Cuci Setrika (per kg)',
                'harga_satuan' => 0,
                'harga_kiloan' => 7000,
            ],
            [
                'nama_item' => 'Cuci Setrika Express (per kg)',
                'harga_satuan' => 0,
                'harga_kiloan' => 10000,
            ],
            [
                'nama_item' => 'Setrika Saja (per kg)',
                'harga_satuan' => 0,
                'harga_kiloan' => 4000,
            ],
            
            // Layanan Satuan - Pakaian Ringan
            [
                'nama_item' => 'Kaos',
                'harga_satuan' => 5000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Kemeja',
                'harga_satuan' => 7000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Celana Pendek',
                'harga_satuan' => 6000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Celana Panjang',
                'harga_satuan' => 8000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Rok',
                'harga_satuan' => 7000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Dress',
                'harga_satuan' => 12000,
                'harga_kiloan' => null,
            ],
            
            // Layanan Satuan - Pakaian Berat
            [
                'nama_item' => 'Jaket',
                'harga_satuan' => 15000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Jas',
                'harga_satuan' => 20000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Coat',
                'harga_satuan' => 25000,
                'harga_kiloan' => null,
            ],
            
            // Layanan Satuan - Lainnya
            [
                'nama_item' => 'Selimut',
                'harga_satuan' => 15000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Bed Cover',
                'harga_satuan' => 20000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Sprei',
                'harga_satuan' => 12000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Karpet Kecil',
                'harga_satuan' => 25000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Karpet Besar',
                'harga_satuan' => 50000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Boneka Kecil',
                'harga_satuan' => 10000,
                'harga_kiloan' => null,
            ],
            [
                'nama_item' => 'Boneka Besar',
                'harga_satuan' => 20000,
                'harga_kiloan' => null,
            ],
        ];

        foreach ($listHarga as $data) {
            DB::table('list_harga')->insert($data);
        }

        $this->command->info('✓ ListHarga seeder completed: 20 items created');
    }
}