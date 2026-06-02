<?php

namespace App\Utilities\SelectHelpers\Transaksi\Penjualan;

use App\Models\Penjualan\SuratJalan;
use App\Utilities\Constants\Const_Status;

class SH_SuratJalan
{
    public static function belumSelesai($include_ids = [])
    {
        $objs = SuratJalan::query()
            ->with(['customer'])
            ->whereIn('status', [Const_Status::SURAT_JALAN_BELUM_SELESAI])
            ->orWhereIn('id', $include_ids)
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s",
                $obj->kode,
                $obj->customer?->nama,
            );
        }

        return $results;
    }
}
