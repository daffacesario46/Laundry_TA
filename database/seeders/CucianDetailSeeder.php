<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CucianDetailSeeder extends Seeder
{
    public function run(): void
    {
        $cucianDetail = [
            // Detail untuk Cucian ID 1 - Andi Wijaya (Cuci Setrika Kiloan)
            [
                'cucian_id' => 1,
                'list_harga_id' => 2, // Cuci Setrika (per kg)
                'jumlah' => 1,
                'berat_kg' => 5.0,
                'deskripsi' => 'Pakaian campur',
            ],
            
            // Detail untuk Cucian ID 2 - Dewi Lestari (Cuci Setrika Express Kiloan)
            [
                'cucian_id' => 2,
                'list_harga_id' => 3, // Cuci Setrika Express (per kg)
                'jumlah' => 1,
                'berat_kg' => 3.5,
                'deskripsi' => 'Pakaian kerja',
            ],
            
            // Detail untuk Cucian ID 3 - Rudi Hartono (Cuci Satuan Premium)
            [
                'cucian_id' => 3,
                'list_harga_id' => 12, // Jas
                'jumlah' => 2,
                'berat_kg' => null,
                'deskripsi' => 'Jas kantor',
            ],
            [
                'cucian_id' => 3,
                'list_harga_id' => 6, // Kemeja
                'jumlah' => 2,
                'berat_kg' => null,
                'deskripsi' => 'Kemeja formal',
            ],
            
            // Detail untuk Cucian ID 4 - Sari Indah (Cuci Kering Kiloan)
            [
                'cucian_id' => 4,
                'list_harga_id' => 1, // Cuci Kering (per kg)
                'jumlah' => 1,
                'berat_kg' => 4.0,
                'deskripsi' => 'Pakaian sehari-hari',
            ],
            
            // Detail untuk Cucian ID 5 - Bambang Sutopo (Dry Cleaning)
            [
                'cucian_id' => 5,
                'list_harga_id' => 12, // Jas
                'jumlah' => 2,
                'berat_kg' => null,
                'deskripsi' => 'Jas untuk meeting',
            ],
            
            // Detail untuk Cucian ID 6 - Ibu Susi (Cuci Setrika Kiloan)
            [
                'cucian_id' => 6,
                'list_harga_id' => 2, // Cuci Setrika (per kg)
                'jumlah' => 1,
                'berat_kg' => 6.0,
                'deskripsi' => 'Pakaian keluarga',
            ],
            
            // Detail untuk Cucian ID 7 - Pak Agus (Setrika Saja Kiloan)
            [
                'cucian_id' => 7,
                'list_harga_id' => 4, // Setrika Saja (per kg)
                'jumlah' => 1,
                'berat_kg' => 3.0,
                'deskripsi' => 'Pakaian sudah dicuci',
            ],
            
            // Detail untuk Cucian ID 8 - Ibu Ratna (Cuci Satuan Premium)
            [
                'cucian_id' => 8,
                'list_harga_id' => 14, // Selimut
                'jumlah' => 1,
                'berat_kg' => null,
                'deskripsi' => 'Selimut tebal',
            ],
            [
                'cucian_id' => 8,
                'list_harga_id' => 15, // Bed Cover
                'jumlah' => 1,
                'berat_kg' => null,
                'deskripsi' => 'Bed cover queen size',
            ],
            [
                'cucian_id' => 8,
                'list_harga_id' => 17, // Karpet Kecil
                'jumlah' => 1,
                'berat_kg' => null,
                'deskripsi' => 'Karpet kamar tidur',
            ],
            
            // Detail untuk Cucian ID 9 - Pak Dedi (Cuci Kering Kiloan)
            [
                'cucian_id' => 9,
                'list_harga_id' => 1, // Cuci Kering (per kg)
                'jumlah' => 1,
                'berat_kg' => 7.5,
                'deskripsi' => 'Pakaian kotor banyak',
            ],
            
            // Detail untuk Cucian ID 10 - Ibu Lina (Cuci Setrika Kiloan)
            [
                'cucian_id' => 10,
                'list_harga_id' => 2, // Cuci Setrika (per kg)
                'jumlah' => 1,
                'berat_kg' => 2.5,
                'deskripsi' => 'Pakaian anak',
            ],
        ];

        foreach ($cucianDetail as $data) {
            DB::table('cucian_detail')->insert($data);
        }

        $this->command->info('✓ CucianDetail seeder completed: 13 detail items created');
    }
}