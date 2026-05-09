<?php

namespace App\Casts;

use App\Utilities\Constants\Const_Date;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsDateCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value == null) {
            return null;
        }

        try {
            $result = Carbon::createFromFormat(Const_Date::DATE_FORMAT_DB, $value)->format(Const_Date::DATE_FORMAT_OUTPUT);
        } catch (Exception $ex) {
            $result = $value;
        }

        return $result;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value == null) {
            return null;
        }

        try {
            $result = Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $value)->format(Const_Date::DATE_FORMAT_DB);
        } catch (Exception $ex) {
            $result = $value;
        }

        return $result;
    }
}
