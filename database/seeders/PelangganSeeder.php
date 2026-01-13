<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        $pelanggan = [
            // Pelanggan Online (punya akun users)
            [
                'users_id' => 6, // Andi Wijaya
                'kategori_pelanggan' => 'online',
                'nama' => 'Andi Wijaya',
                'no_telp' => '081234567895',
                'no_wa' => '081234567895',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => 7, // Dewi Lestari
                'kategori_pelanggan' => 'online',
                'nama' => 'Dewi Lestari',
                'no_telp' => '081234567896',
                'no_wa' => '081234567896',
                'alamat' => 'Jl. Sudirman No. 25, Jakarta Pusat',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => 8, // Rudi Hartono
                'kategori_pelanggan' => 'online',
                'nama' => 'Rudi Hartono',
                'no_telp' => '081234567897',
                'no_wa' => '081234567897',
                'alamat' => 'Jl. Thamrin No. 5, Jakarta Pusat',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => 9, // Sari Indah
                'kategori_pelanggan' => 'online',
                'nama' => 'Sari Indah',
                'no_telp' => '081234567898',
                'no_wa' => '081234567898',
                'alamat' => 'Jl. Gatot Subroto No. 15, Jakarta Selatan',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => 10, // Bambang Sutopo
                'kategori_pelanggan' => 'online',
                'nama' => 'Bambang Sutopo',
                'no_telp' => '081234567899',
                'no_wa' => '081234567899',
                'alamat' => 'Jl. Kuningan No. 20, Jakarta Selatan',
                'foto' => null,
                'status' => 'aktif',
            ],
            
            // Pelanggan Offline (Walk-in Customer - tidak punya akun users)
            [
                'users_id' => null,
                'kategori_pelanggan' => 'offline',
                'nama' => 'Ibu Susi',
                'no_telp' => '081298765432',
                'no_wa' => '081298765432',
                'alamat' => 'Jl. Melati No. 12, Jakarta Barat',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => null,
                'kategori_pelanggan' => 'offline',
                'nama' => 'Pak Agus',
                'no_telp' => '081298765433',
                'no_wa' => '081298765433',
                'alamat' => 'Jl. Mawar No. 7, Jakarta Timur',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => null,
                'kategori_pelanggan' => 'offline',
                'nama' => 'Ibu Ratna',
                'no_telp' => '081298765434',
                'no_wa' => '081298765434',
                'alamat' => 'Jl. Anggrek No. 3, Jakarta Utara',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => null,
                'kategori_pelanggan' => 'offline',
                'nama' => 'Pak Dedi',
                'no_telp' => '081298765435',
                'no_wa' => '081298765435',
                'alamat' => 'Jl. Kenanga No. 18, Tangerang',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'users_id' => null,
                'kategori_pelanggan' => 'offline',
                'nama' => 'Ibu Lina',
                'no_telp' => '081298765436',
                'no_wa' => '081298765436',
                'alamat' => 'Jl. Cempaka No. 22, Bekasi',
                'foto' => null,
                'status' => 'aktif',
            ],
        ];

        foreach ($pelanggan as $data) {
            DB::table('pelanggan')->insert($data);
        }

        $this->command->info('✓ Pelanggan seeder completed: 10 pelanggan created (5 online, 5 offline)');
    }
}