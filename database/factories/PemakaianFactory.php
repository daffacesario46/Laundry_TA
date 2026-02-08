<?php

namespace Database\Factories;

use App\Models\Pemakaian;
use App\Models\Pembelian;
use App\Models\StokBahan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemakaian>
 */
class PemakaianFactory extends Factory
{
    protected $model = Pemakaian::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pembelian_id' => Pembelian::factory(),
            'stok_bahan_id' => StokBahan::factory(),
            'jumlah_terpakai' => fake()->randomFloat(2, 0.5, 5),
            'bukti' => null,
        ];
    }
}
