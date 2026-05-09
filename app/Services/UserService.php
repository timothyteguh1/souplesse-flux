<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Models\Role;
use App\Models\User;

class UserService
{
    public static function create(array $data = []): User
    {
        if (!User::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $user = User::create($data);
        if ($data['role_ids']) {
            $user->assignRole(Role::find($data['role_ids']));
        }
        foreach ($data['cabang_ids'] as $index => $item) {
            if ($item == true) {
                $dataCabang = ['cabang_id' => $index];
                UserCabangService::create($user, $dataCabang);
            }
        }

        foreach ($data['kas_ids'] as $index => $item) {
            if ($item == true) {
                $dataKas = ['kas_id' => $index];
                UserKasService::create($user, $dataKas);
            }
        }

        foreach ($data['gudang_ids'] as $index => $item) {
            if ($item == true) {
                $dataGudang = ['gudang_id' => $index];
                UserGudangService::create($user, $dataGudang);
            }
        }

        return $user;
    }

    public static function update(User $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        if ($data['role_ids']) {
            // hapus role yg lama
            $obj->roles()->detach();
            $obj->refresh();

            // update dengan role yg baru
            $obj->assignRole(Role::find($data['role_ids']));
        }

        $obj->update($data);
        foreach ($obj->userCabangs as $userCabang) {
            UserCabangService::destroy($userCabang);
        }

        foreach ($obj->userKas as $userKas) {
            UserKasService::destroy($userKas);
        }

        foreach ($obj->userGudangs as $userGudang) {
            UserGudangService::destroy($userGudang);
        }

        foreach ($data['cabang_ids'] as $index => $item) {
            if ($item == true) {
                $dataCabang = ['cabang_id' => $index];
                UserCabangService::create($obj, $dataCabang);
            }
        }

        foreach ($data['kas_ids'] as $index => $item) {
            if ($item == true) {
                $dataKas = ['kas_id' => $index];
                UserKasService::create($obj, $dataKas);
            }
        }

        foreach ($data['gudang_ids'] as $index => $item) {
            if ($item == true) {
                $dataGudang = ['gudang_id' => $index];
                UserGudangService::create($obj, $dataGudang);
            }
        }

        return true;
    }

    public static function destroy(User $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        foreach ($obj->userCabangs as $userCabang) {
            UserCabangService::destroy($userCabang);
        }

        foreach ($obj->userKas as $userKas) {
            UserKasService::destroy($userKas);
        }

        foreach ($obj->userGudangs as $userGudang) {
            UserGudangService::destroy($userGudang);
        }

        return $obj->delete();
    }
}
