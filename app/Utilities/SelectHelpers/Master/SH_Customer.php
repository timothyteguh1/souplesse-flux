<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\Customer;
use App\Utilities\Constants\Const_Status;

class SH_Customer
{
    public static function active()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Customer::query()
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

    public static function grosir()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Customer::query()
            ->where('status', Const_Status::AKTIF)
            ->where('cabang_id', $cabang_id)
            ->whereRelation('kelasCustomer', 'nama', 'Grosir')
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
