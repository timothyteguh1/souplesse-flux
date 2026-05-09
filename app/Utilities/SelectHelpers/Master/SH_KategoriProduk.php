<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\KategoriProduk;
use App\Utilities\Constants\Const_Status;

class SH_KategoriProduk
{
    public static function active()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = KategoriProduk::query()
            ->where('cabang_id', $cabang_id)
            ->where('status', Const_Status::AKTIF)
            ->orderBy('nama')
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
