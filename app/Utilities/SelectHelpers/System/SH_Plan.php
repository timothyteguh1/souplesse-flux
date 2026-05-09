<?php

namespace App\Utilities\SelectHelpers\System;

use App\Models\Plan;
use App\Utilities\Constants\Const_Status;

class SH_Plan
{
    public static function active()
    {
        $objs = Plan::query()
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
