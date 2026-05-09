<?php

namespace App\Models;

use App\Traits\HasCoreFeature;
use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    use HasCoreFeature;

    protected $route_prefix = 'admin.system.role';
    protected $permission_prefix = 'admin.system.role';
    protected $with = [
        'permissions',
    ];
}
