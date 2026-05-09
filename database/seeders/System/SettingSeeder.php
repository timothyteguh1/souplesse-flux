<?php

namespace Database\Seeders\System;

use App\Models\Setting;
use App\Utilities\Constants\Const_Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::put(Const_Setting::PPN_PERCENT, 11);
    }
}
