<?php

namespace Database\Seeders\Master;

use App\Models\Master\Cabang;
use App\Models\Master\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'PCS',
            ],
        ];

        foreach ($data as $item) {
            $item['cabang_id'] = Cabang::first()->id;
            Satuan::create($item);
        }
    }
}
