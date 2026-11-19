<?php

namespace Database\Factories;

use App\Models\Pengumuman;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengumuman>
 */
class PengumumanFactory extends Factory
{
    protected $model = Pengumuman::class;

    public function definition(): array
    {
        return [
            'id_user' => null,
            'judul' => fake()->sentence(4),
            'isi' => fake()->paragraph(),
            'status' => Pengumuman::STATUS_DIPUBLIKASIKAN,
            'tanggal_terbit' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => Pengumuman::STATUS_DRAFT,
            'tanggal_terbit' => null,
        ]);
    }
}
