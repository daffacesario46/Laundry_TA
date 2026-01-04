<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting User Seeder...');

        // Admin
        $this->command->info('Creating Admin...');
        User::create([
            'role' => 'admin',
            'email' => 'admin@laundry.com',
            'nama' => 'Administrator',
            'password' => Hash::make('admin123'),
            'no_telp' => '081234567890',
            'no_wa' => '081234567890',
            'alamat' => 'Jl. Admin No. 1, Jakarta',
            'foto' => null,
            'status' => 'aktif',
        ]);

        // Staff 1
        $this->command->info('Creating Staff 1...');
        User::create([
            'role' => 'staff',
            'email' => 'staff1@laundry.com`',
            'nama' => 'Budi Santoso',
            'password' => Hash::make('staff123'),
            'no_telp' => '081234567891',
            'no_wa' => '081234567891',
            'alamat' => 'Jl. Staff No. 1, Jakarta',
            'foto' => null,
            'status' => 'aktif',
        ]);

        // Staff 2
        $this->command->info('Creating Staff 2...');
        User::create([
            'role' => 'staff',
            'email' => 'staff2@laundry.com',
            'nama' => 'Siti Aminah',
            'password' => Hash::make('staff123'),
            'no_telp' => '081234567892',
            'no_wa' => '081234567892',
            'alamat' => 'Jl. Staff No. 2, Jakarta',
            'foto' => null,
            'status' => 'aktif',
        ]);

        // Kurir 1
        $this->command->info('Creating Kurir 1...');
        User::create([
            'role' => 'kurir',
            'email' => 'kurir1@laundry.com',
            'nama' => 'Joko Widodo',
            'password' => Hash::make('kurir123'),
            'no_telp' => '081234567893',
            'no_wa' => '081234567893',
            'alamat' => 'Jl. Kurir No. 1, Jakarta',
            'foto' => null,
            'status' => 'aktif',
        ]);

        // Kurir 2
        $this->command->info('Creating Kurir 2...');
        User::create([
            'role' => 'kurir',
            'email' => 'kurir2@laundry.com',
            'nama' => 'Ahmad Dahlan',
            'password' => Hash::make('kurir123'),
            'no_telp' => '081234567894',
            'no_wa' => '081234567894',
            'alamat' => 'Jl. Kurir No. 2, Jakarta',
            'foto' => null,
            'status' => 'aktif',
        ]);

        // Pelanggan 1 (dengan data pelanggan)
        $this->command->info('Creating Pelanggan 1 with profile...');
        $user1 = User::create([
            'role' => 'pelanggan',
            'email' => 'pelanggan1@gmail.com',
            'nama' => 'Andi Wijaya',
            'password' => Hash::make('pelanggan123'),
            'no_telp' => '081234567895',
            'no_wa' => '081234567895',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan',
            'foto' => null,
            'status' => 'aktif',
        ]);
        Pelanggan::create([
            'users_id' => $user1->users_id,
            'kategori_pelanggan' => 'member',
            'nama' => 'Andi Wijaya',
            'no_telp' => '081234567895',
            'no_wa' => '081234567895',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta Selatan',
            'status' => 'aktif',
        ]);

        // Pelanggan 2
        $this->command->info('Creating Pelanggan 2 with profile...');
        $user2 = User::create([
            'role' => 'pelanggan',
            'email' => 'pelanggan2@gmail.com',
            'nama' => 'Dewi Lestari',
            'password' => Hash::make('pelanggan123'),
            'no_telp' => '081234567896',
            'no_wa' => '081234567896',
            'alamat' => 'Jl. Sudirman No. 25, Jakarta Pusat',
            'foto' => null,
            'status' => 'aktif',
        ]);
        Pelanggan::create([
            'users_id' => $user2->users_id,
            'kategori_pelanggan' => 'member',
            'nama' => 'Dewi Lestari',
            'no_telp' => '081234567896',
            'no_wa' => '081234567896',
            'alamat' => 'Jl. Sudirman No. 25, Jakarta Pusat',
            'status' => 'aktif',
        ]);

        // Pelanggan 3
        $this->command->info('Creating Pelanggan 3 with profile...');
        $user3 = User::create([
            'role' => 'pelanggan',
            'email' => 'pelanggan3@gmail.com',
            'nama' => 'Rudi Hartono',
            'password' => Hash::make('pelanggan123'),
            'no_telp' => '081234567897',
            'no_wa' => '081234567897',
            'alamat' => 'Jl. Thamrin No. 5, Jakarta Pusat',
            'foto' => null,
            'status' => 'aktif',
        ]);
        Pelanggan::create([
            'users_id' => $user3->users_id,
            'kategori_pelanggan' => 'member',
            'nama' => 'Rudi Hartono',
            'no_telp' => '081234567897',
            'no_wa' => '081234567897',
            'alamat' => 'Jl. Thamrin No. 5, Jakarta Pusat',
            'status' => 'aktif',
        ]);

        // Pelanggan 4
        $this->command->info('Creating Pelanggan 4 with profile...');
        $user4 = User::create([
            'role' => 'pelanggan',
            'email' => 'pelanggan4@gmail.com',
            'nama' => 'Sari Indah',
            'password' => Hash::make('pelanggan123'),
            'no_telp' => '081234567898',
            'no_wa' => '081234567898',
            'alamat' => 'Jl. Gatot Subroto No. 15, Jakarta Selatan',
            'foto' => null,
            'status' => 'aktif',
        ]);
        Pelanggan::create([
            'users_id' => $user4->users_id,
            'kategori_pelanggan' => 'member',
            'nama' => 'Sari Indah',
            'no_telp' => '081234567898',
            'no_wa' => '081234567898',
            'alamat' => 'Jl. Gatot Subroto No. 15, Jakarta Selatan',
            'status' => 'aktif',
        ]);

        // Pelanggan 5
        $this->command->info('Creating Pelanggan 5 with profile...');
        $user5 = User::create([
            'role' => 'pelanggan',
            'email' => 'pelanggan5@gmail.com',
            'nama' => 'Bambang Sutopo',
            'password' => Hash::make('pelanggan123'),
            'no_telp' => '081234567899',
            'no_wa' => '081234567899',
            'alamat' => 'Jl. Kuningan No. 20, Jakarta Selatan',
            'foto' => null,
            'status' => 'aktif',
        ]);
        Pelanggan::create([
            'users_id' => $user5->users_id,
            'kategori_pelanggan' => 'member',
            'nama' => 'Bambang Sutopo',
            'no_telp' => '081234567899',
            'no_wa' => '081234567899',
            'alamat' => 'Jl. Kuningan No. 20, Jakarta Selatan',
            'status' => 'aktif',
        ]);

        $this->command->info('');
        $this->command->info('✅ User seeder completed successfully!');
        $this->command->info('==========================================');
        $this->command->info('Total: 10 users created');
        $this->command->info('- 1 Admin');
        $this->command->info('- 2 Staff');
        $this->command->info('- 2 Kurir');
        $this->command->info('- 5 Pelanggan (with profiles)');
        $this->command->info('');
        $this->command->info('Login Credentials:');
        $this->command->info('==========================================');
        $this->command->info('Admin    : admin@laundry.com / admin123');
        $this->command->info('Staff    : staff1@laundry.com / staff123');
        $this->command->info('Kurir    : kurir1@laundry.com / kurir123');
        $this->command->info('Pelanggan: pelanggan1@gmail.com / pelanggan123');
        $this->command->info('==========================================');
    }
}