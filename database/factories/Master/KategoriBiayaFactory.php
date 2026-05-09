<?php

namespace Database\Factories\Master;

use App\Models\Master\Cabang;
use App\Models\Master\KategoriBeban;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriBebanFactory extends Factory
{
    protected $model = KategoriBeban::class;

    public function definition(): array
    {
        return [
            'cabang_id' => Cabang::first()->id,
            'nama' => $this->faker->word(),
            'status' => $this->faker->randomElement([
                Const_Status::AKTIF,
                Const_Status::TIDAK_AKTIF,
            ]),
        ];
    }
}
