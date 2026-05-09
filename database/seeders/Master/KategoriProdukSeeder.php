<?php

namespace Database\Seeders\Master;

use App\Models\Master\Cabang;
use Illuminate\Database\Seeder;
use App\Models\Master\KategoriProduk;

class KategoriProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'OLI',
            ],
            [
                'nama' => 'SEAL',
            ],
            [
                'nama' => 'PAD',
            ],
        ];

        foreach ($data as $item) {
            $item['cabang_id'] = Cabang::first()->id;
            KategoriProduk::create($item);
        }
    }
}
