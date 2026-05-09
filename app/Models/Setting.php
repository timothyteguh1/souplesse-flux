<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Setting extends Model
{
    use KeepsDeletedModels;

    public static function put($key, $value, $group = null)
    {
        $obj = Setting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if (! $obj) {
            $obj = new Setting();
            $obj->key = $key;
            $obj->group = $group;
        }

        $obj->value = $value;
        $obj->save();

        return $obj;
    }

    public static function fetch($key, $group = null)
    {
        $obj = Setting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        if (! $obj) {
            return;
        }

        return $obj->value;
    }

    public static function fetchObject($key, $group = null)
    {
        return Setting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->first();
    }

    public static function hasKey($key, $group = null)
    {
        return Setting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->count();
    }
}
