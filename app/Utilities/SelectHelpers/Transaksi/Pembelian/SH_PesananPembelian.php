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

    public static function selesai($supplier_id, $tanggal = null, $include_ids = [], $is_show_sisa_utang = true)
    {
        $objs = PesananPembelian::query()
            ->with(['details'])
            ->where('supplier_id', $supplier_id)
            ->when(count($include_ids) > 0, function ($query) use ($include_ids) {
                $query->orWhereIn('id', $include_ids);
            })
            ->where('status', Const_Status::PESANAN_PEMBELIAN_SELESAI)
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            if ($is_show_sisa_utang) {
                $results[$obj->id] = sprintf(
                    '[%s] -- Sisa Utang: Rp. %s',
                    $obj->kode,
                    _number($obj->getSisaUtang($tanggal)),
                );
            } else {
                $results[$obj->id] = sprintf(
                    '[%s] (Rp. %s)',
                    $obj->kode,
                    _number($obj->grandtotal),
                );
            }
        }

        return $results;
    }
}
