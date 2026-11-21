<?php

namespace Database\Factories;

use App\Models\StokVitaminObat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StokVitaminObat>
 */
class StokVitaminObatFactory extends Factory
{
    protected $model = StokVitaminObat::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->randomElement(['Vitamin A', 'Zinc', 'Paracetamol Sirup', 'Tablet Tambah Darah', 'Oralit']),
            'jenis' => fake()->randomElement(StokVitaminObat::JENIS),
            'jumlah' => fake()->numberBetween(20, 200),
            'satuan' => fake()->randomElement(['tablet', 'kapsul', 'botol', 'sachet']),
            'tanggal_kedaluwarsa' => StokVitaminObat::hariIni()->addMonths(fake()->numberBetween(3, 24))->toDateString(),
            'stok_minimum' => 10,
        ];
    }
}
