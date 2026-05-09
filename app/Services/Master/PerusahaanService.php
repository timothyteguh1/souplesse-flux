<?php

namespace App\Services\Master;

use App\Models\Master\Perusahaan;
use App\Models\User;
use App\Models\Role;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Support\Facades\Storage;

class PerusahaanService
{
    public static function create(array $data = []): Perusahaan
    {
        $dataUser = [
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'username' => $data['user_username'],
            'password' => $data['user_password'],
            'type' => Const_Umum::USER_TYPE_OWNER,
        ];

        $user = User::create($dataUser);
        $user->assignRole(Role::where('name', $user->type)->first());

        $data['user_id'] = $user->id;

        $obj = Perusahaan::create($data);

        if ($data['logo']) {
            $obj->addMedia($data['logo']->getRealPath())
                ->toMediaCollection();
        }

        return $obj;
    }

    public static function update(Perusahaan $perusahaan, array $data = []): bool
    {
        // sebelumnya sudah ada, lalu di hapus
        if ($perusahaan->logo && blank($data['uploaded_logo']) && blank($data['logo'])) {
            Storage::disk('public')->delete($perusahaan->logo);
            $perusahaan->logo = null;
        }

        // sebelumnya sudah ada, lalu di ubah
        // sebelumnya blm pernah ada
        if (blank($data['uploaded_logo']) && filled($data['logo'])) {
            $logo = $data['logo'];
            $path = $logo->storeAs('logo', 'logo.' . $logo->getClientOriginalExtension(), 'public');
            $perusahaan->logo = $path;
        }

        return $perusahaan->update($data);
    }

    public static function destroy(Perusahaan $perusahaan): bool
    {
        $user = User::find($perusahaan->user_id);
        $perusahaan->delete();
        $user->delete();

        return true;
    }

    public static function getPerusahaanByKode($kode)
    {
        return Perusahaan::where('kode', $kode)->first();
    }

    public static function getPerusahaanByNama($nama)
    {
        return Perusahaan::where('nama', $nama)->first();
    }
}
