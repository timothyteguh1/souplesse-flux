<?php

namespace App\Traits;

use App\Models\System\MutasiTransaksi;

trait HasMutasiTransaksiAsVendor
{
    public function mutasiTransaksi()
    {
        return $this->morphOne(MutasiTransaksi::class, 'vendor');
    }

    public function mutasiTransaksis()
    {
        return $this->morphMany(MutasiTransaksi::class, 'vendor');
    }
}
