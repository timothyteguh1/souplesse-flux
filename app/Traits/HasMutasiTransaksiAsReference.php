<?php

namespace App\Traits;

use App\Models\System\MutasiTransaksi;

trait HasMutasiTransaksiAsReference
{
    public function mutasiTransaksi()
    {
        return $this->morphOne(MutasiTransaksi::class, 'reference');
    }

    public function mutasiTransaksis()
    {
        return $this->morphMany(MutasiTransaksi::class, 'reference');
    }
}
