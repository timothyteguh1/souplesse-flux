<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\ModelProduk;

class ModelProdukService
{
    public static function create(array $data = []): ModelProduk
    {
        if (!ModelProduk::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return ModelProduk::create($data);
    }

    public static function update(ModelProduk $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(ModelProduk $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
