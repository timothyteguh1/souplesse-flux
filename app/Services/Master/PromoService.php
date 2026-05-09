<?php

namespace App\Services\Master;

use App\Models\Master\Promo;
use App\Exceptions\GeneralException;

class PromoService
{
    public static function create(array $data = []): Promo
    {
        if (!Promo::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }
        return Promo::create($data);
    }

    public static function update(Promo $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return $obj->update($data);
    }

    public static function destroy(Promo $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
