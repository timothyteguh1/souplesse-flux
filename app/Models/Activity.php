<?php

namespace App\Models;

use App\Casts\AsDateTimeCast;
use App\Traits\HasCanAction;
use App\Traits\HasRoute;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use HasCanAction;
    use HasRoute;

    protected $route_prefix = 'admin.system.activity-log';
    protected $permission_prefix = 'admin.system.activity-log';
    public $guarded = [];
    protected $casts = [
        'properties' => 'collection',
        'created_at' => AsDateTimeCast::class,
    ];
}
