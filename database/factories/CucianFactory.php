<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class CucianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pelanggan_id' => Pelanggan::factory(),
            'layanan_id' => Layanan::factory(),
            'jenis_order' => $this->faker->randomElement(['online', 'offline']),
            'jenis_ambil' => $this->faker->randomElement(['diantar', 'ambil_sendiri']),
            'tgl_order' => $this->faker->dateTimeBetween('-1 month'),
            'estimasi' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d H:i:s'),
            'total_item' => $this->faker->randomNumber(1, 10),
            'total_berat' => $this->faker->randomFloat(2, 1, 50),
            'total_harga' => $this->faker->randomFloat(2, 10000, 500000),
            'status_cucian' => $this->faker->randomElement(['menunggu', 'diproses', 'selesai', 'diambil']),
            'catatan' => $this->faker->optional()->sentence(),
        ];
    }
}
