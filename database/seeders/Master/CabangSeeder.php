<?php

namespace Database\Seeders\Master;

use App\Models\Master\Cabang;
use App\Models\Master\Perusahaan;
use Illuminate\Database\Seeder;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            ['kode' => 'SBY', 'nama' => 'Surabaya', 'is_pkp' => true],
        ];

        $perusahaan = Perusahaan::first();
        foreach ($data as $item) {
            $item['perusahaan_id'] = $perusahaan->id;
            Cabang::create($item);
        }
    }
}
