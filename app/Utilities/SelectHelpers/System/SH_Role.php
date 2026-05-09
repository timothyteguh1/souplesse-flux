<?php

namespace App\Utilities\SelectHelpers\System;

use App\Models\Role;
use App\Utilities\Constants\Const_Status;

class SH_Role
{
    public static function active()
    {
        $objs = Role::query()
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
}
