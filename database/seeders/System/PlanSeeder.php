<?php

namespace Database\Seeders\System;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'kode' => 'BASIC',
            'nama' => 'Basic',
            'jumlah_cabang' => 1,
            'jumlah_user' => 2,
            'harga' => 100000,
            'masa_aktif_hari' => 30,
        ]);

        Plan::create([
            'kode' => 'PRO',
            'nama' => 'Pro',
            'jumlah_cabang' => 2,
            'jumlah_user' => 10,
            'harga' => 200000,
            'masa_aktif_hari' => 30,
        ]);
    }
}
