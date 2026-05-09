<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Models\Plan;

class PlanService
{
    public static function create(array $data = []): Plan
    {
        return Plan::create($data);
    }

    public static function update(Plan $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(Plan $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
