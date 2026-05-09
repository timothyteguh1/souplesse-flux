<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\JenisProduk;
use App\Utilities\Constants\Const_Status;

class SH_JenisProduk
{
    public static function active()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = JenisProduk::query()
            ->where('cabang_id', $cabang_id)
            ->where('status', Const_Status::AKTIF)
            ->orderBy('kode')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "%s",
                $obj->nama,
            );
        }

        return $results;
    }
}
