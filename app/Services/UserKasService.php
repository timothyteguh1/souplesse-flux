<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserKas;

class UserKasService
{
    public static function create(User $obj, array $data = []): UserKas
    {
        return $obj->userKas()->create($data);
    }

    public static function destroy(UserKas $objDetail): bool
    {
        return $objDetail->delete();
    }
}
