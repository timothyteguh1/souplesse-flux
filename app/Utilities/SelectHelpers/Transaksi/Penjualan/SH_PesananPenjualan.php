<?php

namespace App\Utilities\SelectHelpers\Transaksi\Penjualan;

use App\Models\Penjualan\PesananPenjualan;
use App\Utilities\Constants\Const_Status;

class SH_PesananPenjualan
{
    public static function belumDicetak($include_ids = [])
    {
        $objs = PesananPenjualan::query()
            ->with(['customer.area'])
            ->whereIn('status', [Const_Status::PESANAN_PENJUALAN_BELUM_DICETAK])
            ->orWhereIn('id', $include_ids)
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s -- %s",
                $obj->kode,
                $obj->customer?->nama,
                $obj->customer?->area->nama,
            );
        }

        return $results;
    }

    public static function belumDikirim($include_ids = [])
    {
        $objs = PesananPenjualan::query()
            ->with(['customer.area'])
            ->whereIn('status', [Const_Status::PESANAN_PENJUALAN_BELUM_DIKIRIM])
            ->orWhereIn('id', $include_ids)
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s -- %s",
                $obj->kode,
                $obj->customer?->nama,
                $obj->customer?->area->nama,
            );
        }

        return $results;
    }

    public static function belumDifakturkan($include_ids = [])
    {
        $objs = PesananPenjualan::query()
            ->with(['customer.area'])
            ->whereIn('status', [Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI, Const_Status::PESANAN_PENJUALAN_BELUM_DIKIRIM, Const_Status::PESANAN_PENJUALAN_BELUM_DICETAK])
            ->orWhereIn('id', $include_ids)
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s -- %s",
                $obj->kode,
                $obj->customer?->nama,
                $obj->customer?->area?->nama,
            );
        }

        return $results;
    }
}
