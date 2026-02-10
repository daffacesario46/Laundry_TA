<?php

namespace Database\Factories;

use App\Models\Cucian;
use App\Models\Pelanggan;
use App\Models\Layanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cucian>
 */
class CucianFactory extends Factory
{
    protected $model = Cucian::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pelanggan_id' => Pelanggan::factory(),
            'layanan_id' => Layanan::factory(),
            'jenis_cucian' => fake()->randomElement(['kiloan', 'satuan']),
            'jenis_order' => fake()->randomElement(['online', 'offline']),
            'jenis_ambil' => fake()->randomElement(['ambil_sendiri', 'diantar']),
            'tgl_order' => fake()->dateTime(),
            'estimasi' => fake()->dateTimeBetween('now', '+5 days'),
            'tgl_selesai' => null,
            'tgl_diambil' => null,
            'total_item' => fake()->numberBetween(1, 10),
            'total_berat' => fake()->randomFloat(2, 1, 20),
            'total_harga' => fake()->numberBetween(50000, 500000),
            'status_cucian' => fake()->randomElement(['menunggu', 'diproses', 'selesai', 'diambil']),
            'catatan' => fake()->optional()->sentence(),
            'staff_jemput_id' => null,
            'kurir_antar_id' => null,
        ];
    }

    /**
     * Indicate that the cucian is online order.
     */
    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_order' => 'online',
        ]);
    }

    /**
     * Indicate that the cucian is offline order.
     */
    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_order' => 'offline',
        ]);
    }

    /**
     * Indicate that the cucian is kiloan.
     */
    public function kiloan(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_cucian' => 'kiloan',
        ]);
    }

    /**
     * Indicate that the cucian is satuan.
     */
    public function satuan(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_cucian' => 'satuan',
        ]);
    }
}
