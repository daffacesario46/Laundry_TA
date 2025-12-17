<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PelangganSeeder::class,
            LayananSeeder::class,
            ListHargaSeeder::class,
            StokBahanSeeder::class,
            PembelianSeeder::class,
            CucianSeeder::class,
            CucianDetailSeeder::class,
            PembayaranSeeder::class,
            PenjemputanSeeder::class,
            PengantaranSeeder::class,
            PemakaianSeeder::class,
        ]);

        $this->command->info("\n✓ All seeders completed successfully!");
        $this->command->info("\nLogin Credentials:");
        $this->command->info("Admin    : admin@laundry.com / admin123");
        $this->command->info("Staff    : staff1@laundry.com / staff123");
        $this->command->info("Kurir    : kurir1@laundry.com / kurir123");
        $this->command->info("Pelanggan: pelanggan1@gmail.com / pelanggan123");
    }
}