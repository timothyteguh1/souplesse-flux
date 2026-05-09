<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\Karyawan;
use App\Utilities\Constants\Const_Status;

class SH_Karyawan
{
    public static function active()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Karyawan::query()
            ->where('status', Const_Status::AKTIF)
            ->where('cabang_id', $cabang_id)
            ->orderBy('nama')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s",
                $obj->kode,
                $obj->nama,
            );
        }

        return $results;
    }
}
