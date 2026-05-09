<?php

namespace Database\Seeders\Master;

use App\Models\Master\Cabang;
use App\Models\Master\JenisProduk;
use Illuminate\Database\Seeder;

class JenisProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Sparepart',
            ],
            // [
            //     'nama' => 'Jasa',
            // ],
            // [
            //     'nama' => 'Paket',
            // ],
        ];

        foreach ($data as $item) {
            $item['cabang_id'] = Cabang::first()->id;
            JenisProduk::create($item);
        }
    }
}
