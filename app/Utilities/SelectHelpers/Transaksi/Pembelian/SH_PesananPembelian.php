<?php

namespace App\Utilities\SelectHelpers\Transaksi\Pembelian;

use App\Models\Pembelian\PesananPembelian;
use App\Utilities\Constants\Const_Status;

class SH_PesananPembelian
{
    public static function belumDiterima($supplier_id, $include_ids = [])
    {
        $objs = PesananPembelian::query()
            ->where('supplier_id', $supplier_id)
            ->whereIn('status', [Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA, Const_Status::PESANAN_PEMBELIAN_BELUM_SELESAI])
            ->orWhereIn('id', $include_ids)
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s]",
                $obj->kode,
            );
        }

        return $results;
    }
}
