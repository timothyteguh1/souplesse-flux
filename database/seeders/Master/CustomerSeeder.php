<?php

namespace Database\Seeders\Master;

use App\Models\Master\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::factory()
            ->count(5)
            ->create();
    }
}
