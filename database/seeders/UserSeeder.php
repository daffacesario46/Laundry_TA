<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Admin
            [
                'role' => 'admin',
                'email' => 'admin@laundry.com',
                'nama' => 'Administrator',
                'password' => Hash::make('admin123'),
                'no_telp' => '081234567890',
                'no_wa' => '081234567890',
                'alamat' => 'Jl. Admin No. 1, Jakarta',
                'foto' => null,
                'status' => 'aktif',
            ],
            
            // Staff
            [
                'role' => 'staff',
                'email' => 'staff1@laundry.com',
                'nama' => 'Budi Santoso',
                'password' => Hash::make('staff123'),
                'no_telp' => '081234567891',
                'no_wa' => '081234567891',
                'alamat' => 'Jl. Staff No. 1, Jakarta',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'role' => 'staff',
                'email' => 'staff2@laundry.com',
                'nama' => 'Siti Aminah',
                'password' => Hash::make('staff123'),
                'no_telp' => '081234567892',
                'no_wa' => '081234567892',
                'alamat' => 'Jl. Staff No. 2, Jakarta',
                'foto' => null,
                'status' => 'aktif',
            ],
            
            // Kurir
            [
                'role' => 'kurir',
                'email' => 'kurir1@laundry.com',
                'nama' => 'Joko Widodo',
                'password' => Hash::make('kurir123'),
                'no_telp' => '081234567893',
                'no_wa' => '081234567893',
                'alamat' => 'Jl. Kurir No. 1, Jakarta',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'role' => 'kurir',
                'email' => 'kurir2@laundry.com',
                'nama' => 'Ahmad Dahlan',
                'password' => Hash::make('kurir123'),
                'no_telp' => '081234567894',
                'no_wa' => '081234567894',
                'alamat' => 'Jl. Kurir No. 2, Jakarta',
                'foto' => null,
                'status' => 'aktif',
            ],
            
            // Pelanggan (yang bisa login untuk order online)
            [
                'role' => 'pelanggan',
                'email' => 'pelanggan1@gmail.com',
                'nama' => 'Andi Wijaya',
                'password' => Hash::make('pelanggan123'),
                'no_telp' => '081234567895',
                'no_wa' => '081234567895',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'role' => 'pelanggan',
                'email' => 'pelanggan2@gmail.com',
                'nama' => 'Dewi Lestari',
                'password' => Hash::make('pelanggan123'),
                'no_telp' => '081234567896',
                'no_wa' => '081234567896',
                'alamat' => 'Jl. Sudirman No. 25, Jakarta Pusat',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'role' => 'pelanggan',
                'email' => 'pelanggan3@gmail.com',
                'nama' => 'Rudi Hartono',
                'password' => Hash::make('pelanggan123'),
                'no_telp' => '081234567897',
                'no_wa' => '081234567897',
                'alamat' => 'Jl. Thamrin No. 5, Jakarta Pusat',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'role' => 'pelanggan',
                'email' => 'pelanggan4@gmail.com',
                'nama' => 'Sari Indah',
                'password' => Hash::make('pelanggan123'),
                'no_telp' => '081234567898',
                'no_wa' => '081234567898',
                'alamat' => 'Jl. Gatot Subroto No. 15, Jakarta Selatan',
                'foto' => null,
                'status' => 'aktif',
            ],
            [
                'role' => 'pelanggan',
                'email' => 'pelanggan5@gmail.com',
                'nama' => 'Bambang Sutopo',
                'password' => Hash::make('pelanggan123'),
                'no_telp' => '081234567899',
                'no_wa' => '081234567899',
                'alamat' => 'Jl. Kuningan No. 20, Jakarta Selatan',
                'foto' => null,
                'status' => 'aktif',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }

        $this->command->info('✓ User seeder completed: 10 users created (1 admin, 2 staff, 2 kurir, 5 pelanggan)');
    }
}