<?php

namespace App\Utilities\SelectHelpers\System;

use App\Models\System\MutasiTransaksi;
use App\Utilities\Constants\Const_Status;

class SH_MutasiTransaksi
{
    public static function jenis()
    {
        $objs = MutasiTransaksi::query()
            ->select('jenis')
            ->where('status', Const_Status::AKTIF)
            ->distinct()
            ->orderBy('jenis')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->jenis] = sprintf(
                "%s",
                $obj->jenis,
            );
        }

        return $results;
    }

    public static function jenisTransaksi()
    {
        $objs = MutasiTransaksi::query()
            ->select('jenis_transaksi')
            ->where('status', Const_Status::AKTIF)
            ->distinct()
            ->orderBy('jenis_transaksi')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->jenis_transaksi] = sprintf(
                "%s",
                $obj->jenis_transaksi,
            );
        }

        return $results;
    }
}
