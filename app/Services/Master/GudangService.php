<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Gudang;

class GudangService
{
    public static function create(array $data = []): Gudang
    {
        if (!Gudang::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return Gudang::create($data);
    }

    public static function update(Gudang $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(Gudang $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
