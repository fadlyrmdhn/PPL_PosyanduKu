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
            'judul' => fake()->sentence(4),
            'isi' => fake()->paragraph(),
            'tanggal_terbit' => now()->toDateString(),
            'status' => Pengumuman::STATUS_DIPUBLIKASIKAN,
            'dibuat_oleh' => null,
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
