<?php

namespace App\Utilities\SelectHelpers\System;

use App\Models\System\MutasiStok;
use App\Utilities\Constants\Const_Status;

class SH_MutasiStok
{
    public static function jenisTransaksi()
    {
        $objs = MutasiStok::query()
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
