<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserCabang;

class UserCabangService
{
    public static function create(User $obj, array $data = []): UserCabang
    {
        return $obj->userCabangs()->create($data);
    }

    public static function destroy(UserCabang $objDetail): bool
    {
        return $objDetail->delete();
    }
}
