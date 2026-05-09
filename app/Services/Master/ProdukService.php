<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Produk;

class ProdukService
{
    public static function create(array $data = []): Produk
    {
        if (!Produk::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $data = self::validationNumberNull($data);
        return Produk::create($data);
    }

    public static function update(Produk $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $data = self::validationNumberNull($data);
        $obj->update($data);
        return true;
    }

    public static function destroy(Produk $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }

    public static function validationNumberNull(array $data = []): array
    {
        $data['harga_beli'] = $data['harga_beli'] ?: 0;
        $data['harga_jual'] = $data['harga_jual'] ?: 0;
        $data['stok_minimum'] = $data['stok_minimum'] ?: 0;
        $data['minimal_order'] = $data['minimal_order'] ?: 0;

        return $data;
    }
}
