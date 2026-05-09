<?php

namespace App\Services\Pembelian;

use App\Models\Pembelian\PesananPembelian;
use App\Models\Pembelian\PesananPembelianDetail;

class PesananPembelianDetailService
{
    public static function create(PesananPembelian $obj, array $data = []): PesananPembelianDetail
    {
        $detail = $obj->details()->create($data);

        return $detail;
    }

    public static function update(PesananPembelian $obj, array $data = []): bool
    {
        return $obj->details()->where('id', $data['id'])->update($data);
    }

    public static function destroy(PesananPembelianDetail $objDetail): bool
    {
        return $objDetail->delete();
    }
}
