<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        $layanan = [
            [
                'nama_layanan' => 'Cuci Kering',
                'jenis_cucian' => 'kiloan',
                'deskripsi' => 'Layanan cuci kering per kilogram',
                'durasi_hari' => 2,
            ],
            [
                'nama_layanan' => 'Cuci Setrika',
                'jenis_cucian' => 'kiloan',
                'deskripsi' => 'Layanan cuci setrika per kilogram',
                'durasi_hari' => 3,
            ],
            [
                'nama_layanan' => 'Cuci Setrika Express',
                'jenis_cucian' => 'kiloan',
                'deskripsi' => 'Layanan cuci setrika kiloan dengan pengerjaan cepat',
                'durasi_hari' => 1,
            ],
            [
                'nama_layanan' => 'Setrika Saja',
                'jenis_cucian' => 'kiloan',
                'deskripsi' => 'Layanan setrika saja per kilogram',
                'durasi_hari' => 1,
            ],
            [
                'nama_layanan' => 'Cuci Satuan Premium',
                'jenis_cucian' => 'satuan',
                'deskripsi' => 'Layanan cuci per item dengan penanganan khusus',
                'durasi_hari' => 3,
            ],
            [
                'nama_layanan' => 'Dry Cleaning',
                'jenis_cucian' => 'satuan',
                'deskripsi' => 'Layanan dry cleaning untuk pakaian berbahan khusus',
                'durasi_hari' => 4,
            ],
        ];

        foreach ($layanan as $data) {
            DB::table('layanan')->insert($data);
        }

        $this->command->info('✓ Layanan seeder completed: 6 layanan created');
    }
}