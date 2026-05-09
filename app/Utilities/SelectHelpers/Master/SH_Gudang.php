<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\Gudang;
use App\Utilities\Constants\Const_Status;

class SH_Gudang
{
    public static function active($cabang_id = null)
    {
        if ($cabang_id == null) {
            $cabang_id = session()->get('cabang_id');
        }

        $objs = Gudang::query()
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

    public static function user()
    {
        $cabang_id = session()->get('cabang_id');
        $gudangIds = auth()->user()->getPermissionGudangIds();

        $objs = Gudang::query()
            ->where('status', Const_Status::AKTIF)
            ->where('cabang_id', $cabang_id)
            ->whereIn('id', $gudangIds)
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
