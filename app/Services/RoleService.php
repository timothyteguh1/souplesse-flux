<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public static function create(array $data = []): Role
    {
        if (! Role::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        return DB::transaction(function () use ($data) {
            $role = Role::create($data);

            $newPermissions = [];
            foreach ($data['permissions'] as $permission) {
                $perm = Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);

                $newPermissions[] = $perm->name;
            }

            $role->syncPermissions($newPermissions);

            return $role;
        });
    }

    public static function update(Role $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        return DB::transaction(function () use ($obj, $data) {
            $newPermissions = [];
            foreach ($data['permissions'] as $permission) {
                $perm = Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'web',
                ]);

                $newPermissions[] = $perm->name;
            }

            $obj->syncPermissions($newPermissions);

            return $obj->update($data);
        });
    }

    public static function destroy(Role $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }
}
