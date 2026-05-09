<?php

namespace App\Services\Master;

use App\Models\Master\Produk;
use App\Models\Master\ProdukSatuan;

class ProdukSatuanService
{
    public static function create(Produk $obj, array $data = []): ProdukSatuan
    {
        return $obj->produkSatuans()->create($data);
    }

    public static function update(ProdukSatuan $objDetail, array $data = []): bool
    {
        return $objDetail->update($data);
    }

    public static function destroy(ProdukSatuan $objDetail): bool
    {
        return $objDetail->delete();
    }
}
