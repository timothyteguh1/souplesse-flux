<?php

namespace App\Utilities\SelectHelpers\System;

use Spatie\Activitylog\Models\Activity;

class SH_ActivityLog
{
    public static function events()
    {
        $objs = Activity::query()
            ->select('event')
            ->distinct()
            ->orderBy('event')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->event] = sprintf(
                "%s",
                $obj->event,
            );
        }

        return $results;
    }
}
