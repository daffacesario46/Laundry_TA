<?php

namespace Database\Factories;

use App\Models\Pembayaran;
use App\Models\Cucian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembayaran>
 */
class PembayaranFactory extends Factory
{
    protected $model = Pembayaran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cucian_id' => Cucian::factory(),
            'metode_bayar' => fake()->randomElement(['cash', 'transfer']),
            'status_bayar' => fake()->randomElement(['lunas', 'belum']),
            'jumlah_bayar' => fake()->numberBetween(50000, 500000),
            'tgl_bayar' => fake()->optional()->dateTime(),
            'bukti_bayar' => null,
            'catatan' => fake()->optional()->sentence(),
            'snap_token' => null,
            'transaction_id' => null,
            'payment_type' => null,
            'transaction_status' => null,
        ];
    }

    /**
     * Indicate that the pembayaran is lunas.
     */
    public function lunas(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_bayar' => 'lunas',
            'tgl_bayar' => fake()->dateTime(),
        ]);
    }

    /**
     * Indicate that the pembayaran is belum.
     */
    public function belum(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_bayar' => 'belum',
            'tgl_bayar' => null,
        ]);
    }
}
