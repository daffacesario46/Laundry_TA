<?php

namespace Database\Factories;

use App\Models\ListHarga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListHarga>
 */
class ListHargaFactory extends Factory
{
    protected $model = ListHarga::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_item' => fake()->words(2, true),
            'harga_satuan' => fake()->numberBetween(5000, 50000),
            'harga_kiloan' => fake()->optional()->numberBetween(20000, 100000),
        ];
    }
}
