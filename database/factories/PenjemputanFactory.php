<?php

namespace Database\Factories;

use App\Models\Penjemputan;
use App\Models\Cucian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penjemputan>
 */
class PenjemputanFactory extends Factory
{
    protected $model = Penjemputan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cucian_id' => Cucian::factory(),
            'staff_id' => User::factory(),
            'alamat_jemput' => fake()->address(),
            'status' => fake()->randomElement(['menunggu', 'diproses', 'selesai']),
            'tgl_order' => fake()->dateTime(),
            'foto' => null,
            'catatan' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the penjemputan is completed.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }
}
