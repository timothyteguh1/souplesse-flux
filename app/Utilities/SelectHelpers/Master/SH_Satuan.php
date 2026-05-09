<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\Satuan;
use App\Utilities\Constants\Const_Status;

class SH_Satuan
{
    public static function active()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Satuan::query()
            ->where('cabang_id', $cabang_id)
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
