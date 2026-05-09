<?php

namespace App\Utilities\SelectHelpers\System;

use App\Models\User;
use App\Models\Master\Kasir;
use App\Models\Master\Karyawan;
use App\Utilities\Constants\Const_Status;

class SH_User
{
    public static function active()
    {
        $objs = User::query()
            ->where('status', Const_Status::AKTIF)
            ->orderBy('name')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "%s",
                $obj->name,
            );
        }

        return $results;
    }

    public static function karyawan($user_id = null)
    {
        $user_used = Karyawan::get()->pluck('user_id')->filter();
        $objs = User::query()
            ->where('status', Const_Status::AKTIF)
            ->whereNotIn('id', $user_used)
            ->when($user_id, function ($query, $user_id) {
                return $query->orWhere('id', $user_id);
            })
            ->orderBy('name')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "%s",
                $obj->name,
            );
        }

        return $results;
    }

    public static function kasir($kasir_id)
    {

        $obj = Kasir::query()
            ->where('id', $kasir_id)
            ->where('status', Const_Status::AKTIF)
            ->orderBy('nama')
            ->first();

        $results = [];
        foreach ($obj->kasirUsers()->with('user')->get() as $obj) {
            $results[$obj->user_id] = sprintf(
                "%s",
                $obj->user->name,
            );
        }

        return $results;
    }
}
