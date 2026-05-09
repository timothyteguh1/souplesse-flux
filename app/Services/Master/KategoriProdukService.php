<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\KategoriProduk;

class KategoriProdukService
{
    public static function create(array $data = []): KategoriProduk
    {
        if (! KategoriProduk::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return KategoriProduk::create($data);
    }

    public static function update(KategoriProduk $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(KategoriProduk $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
