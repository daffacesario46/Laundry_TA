<?php

namespace Database\Factories;

use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian>
 */
class PembelianFactory extends Factory
{
    protected $model = Pembelian::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_beli' => 'PBL-' . fake()->unique()->numerify('#####'),
            'wkt_beli' => fake()->dateTime(),
            'tanggal_beli' => fake()->date(),
            'jam_beli' => fake()->time(),
            'jenis_bahan' => fake()->randomElement(['Deterjen', 'Pewangi', 'Softener', 'Pemutih', 'Anti Noda']),
            'merk' => fake()->randomElement(['Attack', 'Rinso', 'Daia', 'So Klin', 'Molto']),
            'jumlah_beli' => fake()->randomFloat(2, 1, 50),
            'total_harga' => fake()->numberBetween(50000, 500000),
            'bukti' => null,
        ];
    }
}
