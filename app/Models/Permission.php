<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;
use Spatie\Permission\Models\Permission as BasePermission;

class Permission extends BasePermission
{
    use HasUuids;
    use KeepsDeletedModels;
}
