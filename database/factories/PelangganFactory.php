<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelanggan>
 */
class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_id' => User::factory(),
            'kategori_pelanggan' => fake()->randomElement(['member', 'umum']),
            'nama' => fake()->name(),
            'no_telp' => fake()->phoneNumber(),
            'no_wa' => fake()->phoneNumber(),
            'alamat' => fake()->address(),
            'foto' => null,
            'status' => fake()->randomElement(['aktif', 'nonaktif']),
        ];
    }

    /**
     * Indicate that the pelanggan is a member.
     */
    public function member(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori_pelanggan' => 'member',
        ]);
    }

    /**
     * Indicate that the pelanggan is active.
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
        ]);
    }
}
