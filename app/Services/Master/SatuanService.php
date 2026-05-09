<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Satuan;

class SatuanService
{
    public static function create(array $data = []): Satuan
    {
        if (! Satuan::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return Satuan::create($data);
    }

    public static function update(Satuan $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(Satuan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
