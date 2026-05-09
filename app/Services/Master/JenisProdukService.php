<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\JenisProduk;

class JenisProdukService
{
    public static function create(array $data = []): JenisProduk
    {
        if (!JenisProduk::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return JenisProduk::create($data);
    }

    public static function update(JenisProduk $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(JenisProduk $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
