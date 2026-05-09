<?php

namespace App\Casts;

use App\Utilities\Constants\Const_Date;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class AsDateTimeCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value == null) {
            return null;
        }

        try {
            if ($value instanceof Carbon) {
                return $value->format(Const_Date::DATETIME_FORMAT_OUTPUT);
            }

            if ($value instanceof DateTime) {
                return $value->format(Const_Date::DATETIME_FORMAT_OUTPUT);
            }

            $result = Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_DB, $value)->format(Const_Date::DATETIME_FORMAT_OUTPUT);
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
            if ($value instanceof Carbon) {
                return $value->format(Const_Date::DATETIME_FORMAT_DB);
            }

            if ($value instanceof DateTime) {
                return $value->format(Const_Date::DATETIME_FORMAT_DB);
            }

            $result = Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_OUTPUT, $value)->format(Const_Date::DATETIME_FORMAT_DB);
        } catch (Exception $ex) {
            $result = $value;
        }

        return $result;
    }
}
