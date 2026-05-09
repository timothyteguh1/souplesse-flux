<?php

namespace App\Services\Penjualan;

use App\Models\Penjualan\FakturPenjualan;
use App\Models\Penjualan\FakturPenjualanBeban;

class FakturPenjualanBebanService
{
    public static function create(FakturPenjualan $obj, array $data = []): FakturPenjualanBeban
    {
        $detail = $obj->fakturPenjualanBebans()->create($data);
        return $detail;
    }

    public static function update(FakturPenjualan $obj, array $data = []): bool
    {
        return $obj->fakturPenjualanBebans()->where('id', $data['id'])->update($data);
    }

    public static function destroy(FakturPenjualanBeban $objDetail): bool
    {
        return $objDetail->delete();
    }
}
