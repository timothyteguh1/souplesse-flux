<?php

namespace App\Utilities\SelectHelpers\Transaksi\Pembelian;

use App\Models\Pembelian\FakturPembelian;
use App\Utilities\Constants\Const_Status;

class SH_FakturPembelian
{
    public static function belumLunas($supplier_id, $tanggal = null, $include_ids = [], $is_show_sisa_utang = true)
    {
        $objs = FakturPembelian::query()
            ->with(['details'])
            ->where('supplier_id', $supplier_id)
            ->when(count($include_ids) > 0, function ($query) use ($include_ids) {
                $query->where('status', Const_Status::FAKTUR_PEMBELIAN_BELUM_LUNAS);
                $query->orWhereIn('id', $include_ids);
            })
            ->when(count($include_ids) == 0, function ($query) {
                $query->where('status', Const_Status::FAKTUR_PEMBELIAN_BELUM_LUNAS);
            })
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
                    '[%s (Rp. %s)]',
                    $obj->kode,
                    _number($obj->grandtotal),
                );
            }
        }

        return $results;
    }

    public static function all($supplier_id, $tanggal = null, $include_ids = [], $is_show_sisa_utang = true)
    {
        $objs = FakturPembelian::query()
            ->with(['details'])
            ->where('supplier_id', $supplier_id)
            ->when(count($include_ids) > 0, function ($query) use ($include_ids) {
                $query->orWhereIn('id', $include_ids);
            })
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            if ($is_show_sisa_utang) {
                $results[$obj->id] = sprintf(
                    '[%s || %s] -- Sisa Utang: Rp. %s',
                    $obj->kode,
                    $obj->kode_faktur_supplier,
                    _number($obj->getSisaUtang($tanggal)),
                );
            } else {
                $results[$obj->id] = sprintf(
                    '[%s || %s] (Rp. %s)',
                    $obj->kode,
                    $obj->kode_faktur_supplier,
                    _number($obj->grandtotal),
                );
            }
        }

        return $results;
    }
}
