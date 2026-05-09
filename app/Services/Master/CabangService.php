<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Cabang;
use App\Models\Master\Perusahaan;

class CabangService
{
    public static function create(array $data = []): Cabang
    {
        if (!Cabang::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $data['perusahaan_id'] = Perusahaan::first()->id;

        $obj = Cabang::create($data);
        if ($data['input_logo']) {
            $obj->addMedia($data['input_logo']->getRealPath())
                ->toMediaCollection();
        }
        if ($data['input_ktp_foto']) {
            $obj->addMedia($data['input_ktp_foto']->getRealPath())
                ->toMediaCollection('ktp_foto');
        }
        if ($data['input_npwp_foto']) {
            $obj->addMedia($data['input_npwp_foto']->getRealPath())
                ->toMediaCollection('npwp_foto');
        }
        if ($data['input_sia_foto']) {
            $obj->addMedia($data['input_sia_foto']->getRealPath())
                ->toMediaCollection('sia_foto');
        }
        if ($data['input_sipa_foto']) {
            $obj->addMedia($data['input_sipa_foto']->getRealPath())
                ->toMediaCollection('sipa_foto');
        }

        return $obj;
    }

    public static function update(Cabang $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        if ($data['input_logo']) {
            $obj->clearMediaCollection('default');
            $obj->addMedia($data['input_logo']->getRealPath())
                ->toMediaCollection();
        }

        if ($data['input_ktp_foto']) {
            $obj->clearMediaCollection('ktp_foto');

            $obj->addMedia($data['input_ktp_foto']->getRealPath())
                ->toMediaCollection('ktp_foto');
        }

        if ($data['input_npwp_foto']) {
            $obj->clearMediaCollection('npwp_foto');

            $obj->addMedia($data['input_npwp_foto']->getRealPath())
                ->toMediaCollection('npwp_foto');
        }

        if ($data['input_sia_foto']) {
            $obj->clearMediaCollection('sia_foto');

            $obj->addMedia($data['input_sia_foto']->getRealPath())
                ->toMediaCollection('sia_foto');
        }

        if ($data['input_sipa_foto']) {
            $obj->clearMediaCollection('sipa_foto');

            $obj->addMedia($data['input_sipa_foto']->getRealPath())
                ->toMediaCollection('sipa_foto');
        }

        return $obj->update($data);
    }

    public static function destroy(Cabang $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        $obj->clearMediaCollection();
        $obj->clearMediaCollection('ktp_foto');
        $obj->clearMediaCollection('npwp_foto');
        $obj->clearMediaCollection('sia_foto');
        $obj->clearMediaCollection('sipa_foto');

        return $obj->delete();
    }
}
