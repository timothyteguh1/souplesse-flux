<?php

namespace Database\Factories\Master;

use App\Models\Master\Area;
use App\Models\Master\Cabang;
use App\Models\Master\Customer;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'cabang_id' => Cabang::first()->id,
            'nama' => $this->faker->word(),
            'area_id' => Area::first()->id,
            'telp' => $this->faker->word(),
            'handphone' => $this->faker->phoneNumber(),
            'whatsapp' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'fax' => $this->faker->phoneNumber(),
            'website' => $this->faker->word(),
            'alamat' => $this->faker->word(),
            'kota' => $this->faker->word(),
            'kode_pos' => $this->faker->word(),
            'provinsi' => $this->faker->word(),
            'jatuh_tempo' => $this->faker->numberBetween(0, 50),
            'limit_piutang' => $this->faker->numberBetween(100000000, 200000000),
            'status' => $this->faker->randomElement([
                Const_Status::AKTIF,
            ]),
        ];
    }
}
