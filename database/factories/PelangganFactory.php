<?php
namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelangganFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'no_telp' => fake()->phoneNumber(),
            'no_wa' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'kategori_pelanggan' => fake()->randomElement(['regular', 'vip']),
        ];
    }
}
