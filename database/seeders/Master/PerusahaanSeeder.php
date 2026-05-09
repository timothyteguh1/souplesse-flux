<?php

namespace Database\Seeders\Master;

use App\Models\Master\Perusahaan;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataUser = [
            'name' => 'Owner 1',
            'email' => 'owner@email.com',
            'username' => 'owner1',
            'password' => 'owner1',
            'type' => Const_Umum::USER_TYPE_OWNER,
        ];

        $user = User::create($dataUser);
        $user->assignRole(Role::where('name', $user->type)->first());

        $plan = Plan::first();

        Perusahaan::create([
            'id' => '9787c90e-9dcd-4ecc-a432-72ea1e81e726',
            'kode' => 'CV',
            'nama' => 'CV ABC',
            'alamat' => 'ALAMAT',
            'provinsi' => 'Jawa Timur',
            'kota' => 'Surabaya',
            'telp' => '(000) 123456789',
            'fax' => '(111) 123456789',
            'email' => 'abc@yahoo.co.id',
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);
    }
}
