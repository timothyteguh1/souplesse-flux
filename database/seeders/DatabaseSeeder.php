<?php

namespace Database\Seeders;

use Database\Seeders\Master\AreaSeeder;
use Database\Seeders\Master\BebanSeeder;
use Database\Seeders\Master\BrandSeeder;
use Database\Seeders\Master\CabangSeeder;
use Database\Seeders\Master\ChannelCustomerSeeder;
use Database\Seeders\Master\CustomerSeeder;
use Database\Seeders\Master\GudangSeeder;
use Database\Seeders\Master\JenisProdukSeeder;
use Database\Seeders\Master\KasSeeder;
use Database\Seeders\Master\KategoriBebanSeeder;
use Database\Seeders\Master\KategoriProdukSeeder;
use Database\Seeders\Master\KelasCustomerSeeder;
use Database\Seeders\Master\PendapatanSeeder;
use Database\Seeders\Master\PerusahaanSeeder;
use Database\Seeders\Master\ProdukSeeder;
use Database\Seeders\Master\SatuanSeeder;
use Database\Seeders\Master\SupplierSeeder;
use Database\Seeders\System\PlanSeeder;
use Database\Seeders\System\RolesSeeder;
use Database\Seeders\System\SettingSeeder;
use Database\Seeders\System\SystemPermissionSeeder;
use Database\Seeders\System\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        Artisan::call('cache:clear');
        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

        activity()->disableLogging();

        // Seeder System
        $this->call(SystemPermissionSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);

        // Seeder Master
        $this->call(PlanSeeder::class);
        $this->call(PerusahaanSeeder::class);
        $this->call(CabangSeeder::class);
        $this->call(GudangSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(SatuanSeeder::class);
        $this->call(JenisProdukSeeder::class);
        $this->call(KategoriProdukSeeder::class);

        // Seeder Transaksi

        // Seeder Laporan

        // Seeder Setting
        $this->call(SettingSeeder::class);

        activity()->enableLogging();
    }
}
