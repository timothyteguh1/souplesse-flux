<?php

namespace App\Services\Penjualan;

use App\Models\Penjualan\PesananPenjualan;
use App\Models\Penjualan\PesananPenjualanDetail;

class PesananPenjualanDetailService
{
    public static function create(PesananPenjualan $obj, array $data = []): PesananPenjualanDetail
    {
        $detail = $obj->details()->create($data);

        return $detail;
    }

    public static function update(PesananPenjualan $obj, array $data = []): bool
    {
        return $obj->details()->where('id', $data['id'])->update($data);
    }

    public static function destroy(PesananPenjualanDetail $objDetail): bool
    {
        return $objDetail->delete();
    }
}
