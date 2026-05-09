<?php

namespace Database\Factories\Master;

use App\Models\Master\Satuan;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class SatuanFactory extends Factory
{
    protected $model = Satuan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'status' => $this->faker->randomElement([
                Const_Status::AKTIF,
                Const_Status::TIDAK_AKTIF,
            ]),
        ];
    }
}
