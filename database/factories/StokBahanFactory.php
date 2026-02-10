<?php

namespace Database\Factories;

use App\Models\StokBahan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StokBahan>
 */
class StokBahanFactory extends Factory
{
    protected $model = StokBahan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenis_bahan' => fake()->randomElement(['Deterjen', 'Pelembut', 'Pewangi', 'Pemutih']),
            'merk' => fake()->company(),
            'stok_tersedia' => fake()->randomFloat(2, 10, 100),
            'satuan' => fake()->randomElement(['kg', 'liter', 'pcs']),
            'harga_beli' => fake()->numberBetween(10000, 100000),
            'stok_minimum' => fake()->randomFloat(2, 5, 20),
            'deskripsi' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the stok is low.
     */
    public function stokRendah(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok_tersedia' => 5,
            'stok_minimum' => 10,
        ]);
    }

    /**
     * Indicate that the stok is empty.
     */
    public function stokHabis(): static
    {
        return $this->state(fn (array $attributes) => [
            'stok_tersedia' => 0,
        ]);
    }
}
