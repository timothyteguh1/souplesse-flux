<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Supplier;

class SupplierService
{
    public static function create(array $data = []): Supplier
    {
        if (! Supplier::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return Supplier::create($data);
    }

    public static function update(Supplier $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(Supplier $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
