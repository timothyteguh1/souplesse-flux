<?php

namespace Database\Seeders\Master;

use App\Models\Master\Cabang;
use App\Models\Master\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'PT BAROKAH',
                'telp' => '',
                'alamat' => 'AHMAD YANI 12',
                'kota' => '',
            ],
        ];

        foreach ($data as $item) {
            $item['cabang_id'] = Cabang::first()->id;
            Supplier::create($item);
        }
    }
}
