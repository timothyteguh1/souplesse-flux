<?php

namespace Database\Seeders\Master;

use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use Illuminate\Database\Seeder;

class GudangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Utama'],
            ['nama' => 'Retur'],
        ];

        foreach ($data as $item) {
            $item['cabang_id'] = Cabang::first()->id;
            Gudang::create($item);
        }
    }
}
