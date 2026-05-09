<?php

namespace Database\Factories\Master;

use App\Models\Master\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cabang_id' => Cabang::first()->id,
            'nama' => $this->faker->name(),
            'telp' => $this->faker->phoneNumber(),
            'handphone' => $this->faker->phoneNumber(),
            'whatsapp' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'alamat' => $this->faker->address(),
            'kota' => $this->faker->city(),
            'kode_pos' => $this->faker->randomDigitNotZero(),
            'jatuh_tempo' => $this->faker->numberBetween(1, 90),
        ];
    }
}
