<?php

namespace App\Utilities\SelectHelpers\Transaksi\Penjualan;

use App\Models\Penjualan\FakturPenjualan;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Status;
use Carbon\Carbon;

class SH_FakturPenjualan
{
    public static function active($customer_id)
    {
        $objs = FakturPenjualan::where('customer_id', $customer_id)
            ->where('status', Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS)
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                '[%s] -- Sisa Tagihan: %s',
                $obj->kode,
                _number($obj->sisa_bayar),
            );
        }

        return $results;
    }

    public static function belumDikirim()
    {
        $objs = FakturPenjualan::with('customer')
            ->whereIn('status_pengiriman', [Const_Status::PENGIRIMAN_DETAIL_BELUM_DIKIRIM, Const_Status::PENGIRIMAN_DETAIL_GAGAL_ANTAR])
            ->orderBy('status_pengiriman', 'desc')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                '[%s] -- Customer: %s -- Status Pengiriman: %s',
                $obj->kode,
                $obj->customer->nama,
                $obj->status_pengiriman,
            );
        }

        return $results;
    }

    public static function belumLunas($customer_id, $tanggal = null, $include_ids = [], $is_show_sisa_piutang = true)
    {

        $tanggal = $tanggal ? Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_OUTPUT, $tanggal) : $tanggal;
        $objs = FakturPenjualan::query()
            ->with(['details'])
            ->where('customer_id', $customer_id)
            ->when(count($include_ids) > 0, function ($query) use ($include_ids) {
                $query->where('status', Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS);
                $query->orWhereIn('id', $include_ids);
            })
            ->when(count($include_ids) == 0, function ($query) {
                $query->where('status', Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS);
            })
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            if ($is_show_sisa_piutang) {
                $results[$obj->id] = sprintf(
                    '[%s] -- Sisa Tagihan: %s',
                    $obj->kode,
                    _number($obj->getSisaTagihan($tanggal)),
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
}
