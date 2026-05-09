<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

trait HasCoreFeature
{
    use HasCanAction;
    use HasFactory;
    use HasKeywordSearch;
    use HasLogActivityOptions;
    use HasRoute;
    use HasUuids;
    use KeepsDeletedModels;
    use LogsActivity;

    public static function getTableName()
    {
        return (new self())->getTable();
    }
}
