<?php

namespace Database\Factories;

use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Layanan>
 */
class LayananFactory extends Factory
{
    protected $model = Layanan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_layanan' => fake()->randomElement(['Cuci Kiloan', 'Cuci Satuan', 'Cuci Express']),
            'jenis_cucian' => fake()->randomElement(['kiloan', 'satuan']),
            'deskripsi' => fake()->sentence(),
            'durasi_hari' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Indicate that the layanan is kiloan.
     */
    public function kiloan(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_cucian' => 'kiloan',
        ]);
    }

    /**
     * Indicate that the layanan is satuan.
     */
    public function satuan(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_cucian' => 'satuan',
        ]);
    }
}
