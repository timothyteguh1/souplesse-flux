<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserGudang;

class UserGudangService
{
    public static function create(User $obj, array $data = []): UserGudang
    {
        return $obj->userGudangs()->create($data);
    }

    public static function destroy(UserGudang $objDetail): bool
    {
        return $objDetail->delete();
    }
}
