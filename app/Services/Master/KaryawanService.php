<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Karyawan;

class KaryawanService
{
    public static function create(array $data = []): Karyawan
    {
        $data = self::validationNull($data);
        return Karyawan::create($data);
    }

    public static function update(Karyawan $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $data = self::validationNull($data);
        return $obj->update($data);
    }

    public static function destroy(Karyawan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }

    public static function validationNull(array $data = []): array
    {
        $data['komisi'] = $data['komisi'] ?: 0;

        return $data;
    }
}
