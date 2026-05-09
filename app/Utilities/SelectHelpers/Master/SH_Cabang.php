<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\Cabang;
use App\Utilities\Constants\Const_Status;

class SH_Cabang
{
    public static function active($cabang_ids = null)
    {
        $objs = Cabang::query()
            ->where('status', Const_Status::AKTIF)
            ->when($cabang_ids, function ($query, $cabang_ids) {
                return $query->whereIn('id', $cabang_ids);
            })
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

    public static function user()
    {
        $cabangIds = auth()->user()->getPermissionCabangIds();

        $objs = Cabang::query()
            ->where('status', Const_Status::AKTIF)
            ->whereIn('id', $cabangIds)
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
