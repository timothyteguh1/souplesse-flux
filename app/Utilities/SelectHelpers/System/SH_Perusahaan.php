<?php

namespace App\Utilities\SelectHelpers\System;

use App\Models\Master\Perusahaan;
use App\Utilities\Constants\Const_Status;

class SH_Perusahaan
{
    public static function active()
    {
        $objs = Perusahaan::query()
            ->where('status', Const_Status::AKTIF)
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
