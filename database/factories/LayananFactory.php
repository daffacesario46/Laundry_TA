<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayananFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_layanan' => fake()->randomElement(['Cuci Kering', 'Setrika', 'Cuci Basah']),
            'jenis_cucian' => fake()->randomElement(['kiloan', 'satuan']),
            'deskripsi' => fake()->sentence(),
            'durasi_hari' => fake()->numberBetween(1, 3)
        ];
    }
}
