<?php

namespace Database\Factories;

use App\Models\Pengantaran;
use App\Models\Cucian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengantaran>
 */
class PengantaranFactory extends Factory
{
    protected $model = Pengantaran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cucian_id' => Cucian::factory(),
            'kurir_id' => User::factory(),
            'alamat_antar' => fake()->address(),
            'status' => fake()->randomElement(['menunggu', 'diproses', 'selesai']),
            'tgl_berangkat' => fake()->optional()->dateTime(),
            'foto' => null,
            'catatan' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the pengantaran is completed.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }
}
