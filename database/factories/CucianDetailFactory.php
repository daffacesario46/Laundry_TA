<?php

namespace Database\Factories;

use App\Models\CucianDetail;
use App\Models\Cucian;
use App\Models\ListHarga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CucianDetail>
 */
class CucianDetailFactory extends Factory
{
    protected $model = CucianDetail::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cucian_id' => Cucian::factory(),
            'list_harga_id' => ListHarga::factory(),
            'jumlah' => fake()->numberBetween(1, 10),
            'berat_kg' => fake()->optional()->randomFloat(2, 0.5, 10),
            'harga_satuan' => fake()->numberBetween(5000, 50000),
            'harga_kiloan' => fake()->optional()->numberBetween(20000, 100000),
            'deskripsi' => fake()->optional()->sentence(),
        ];
    }
}
