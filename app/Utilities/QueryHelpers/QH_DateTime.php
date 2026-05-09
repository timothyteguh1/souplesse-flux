<?php

namespace App\Utilities\QueryHelpers;

use DB;

class QH_DateTime
{
    public static function scopeDateRange($query, $date_range, $column_name)
    {
        $tanggals = explode(' - ', $date_range);
        $tanggal_awal = _date_format_db($tanggals[0]);
        if (count($tanggals) > 1) {
            $tanggal_akhir = _date_format_db($tanggals[1]);

            $query->whereDate($column_name, '>=', $tanggal_awal)
                ->whereDate($column_name, '<=', $tanggal_akhir);
        } else {
            $query->whereDate($column_name, $tanggal_awal);
        }

        return $query;
    }

    public static function scopeDateRangeRelation($query, $date_range, $relation_name, $column_name)
    {
        $tanggals = explode(' - ', $date_range);
        $tanggal_awal = _date_format_db($tanggals[0]);
        if (count($tanggals) > 1) {
            $tanggal_akhir = _date_format_db($tanggals[1]);
            $query->whereRelation($relation_name, DB::raw("DATE({$column_name})"), '>=', $tanggal_awal)
                ->whereRelation($relation_name, DB::raw("DATE({$column_name})"), '<=', $tanggal_akhir);
        } else {
            $query->whereRelation($relation_name, DB::raw("DATE({$column_name})"), $tanggal_awal);
        }

        return $query;
    }

    public static function scopeDateTimeRange($query, $date_range, $column_name)
    {
        $tanggals = explode(' - ', $date_range);
        $tanggal_awal = _datetime_format_db($tanggals[0]);
        if (count($tanggals) > 1) {
            $tanggal_akhir = _datetime_format_db($tanggals[1]);

            $query->where($column_name, '>=', $tanggal_awal)
                ->where($column_name, '<=', $tanggal_akhir);
        } else {
            $query->where($column_name, $tanggal_awal);
        }

        return $query;
    }

    public static function scopeDateRangeTimeRelation($query, $date_range, $relation_name, $column_name)
    {
        $tanggals = explode(' - ', $date_range);
        $tanggal_awal = _datetime_format_db($tanggals[0]);
        if (count($tanggals) > 1) {
            $tanggal_akhir = _datetime_format_db($tanggals[1]);
            $query->whereRelation($relation_name, $column_name, '>=', $tanggal_awal)
                ->whereRelation($relation_name, $column_name, '<=', $tanggal_akhir);
        } else {
            $query->whereRelation($relation_name, $column_name, $tanggal_awal);
        }

        return $query;
    }
}
