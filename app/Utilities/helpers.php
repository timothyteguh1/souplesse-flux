<?php

use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

if (!function_exists('_get_class_name')) {
    function _get_class_name($model)
    {
        $classNameWithNamespace = get_class($model);

        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
    }
}

if (!function_exists('_number')) {
    function _number($amount, $file_type = Const_Umum::FILETYPE_WEB)
    {
        if ($file_type == Const_Umum::FILETYPE_XLSX) {
            return $amount;
        }

        return _get_formatted_number($amount);
    }
}

if (!function_exists('_numberReport')) {
    function _numberReport($amount, $file_type = Const_Umum::FILETYPE_WEB)
    {
        if ($file_type == Const_Umum::FILETYPE_XLSX) {
            return _round($amount, 2);
        }

        return _get_formatted_number($amount, 2);
    }
}

if (!function_exists('_get_formatted_number')) {
    function _get_formatted_number(
        $value,
        $decimals = 2,
        $decimal_sep = ',',
        $thousands_sep = '.',
        $trimDecimalZeroes = true,
    ) {
        $result = number_format($value, $decimals, $decimal_sep, $thousands_sep);

        if ($trimDecimalZeroes && strpos($result, $decimal_sep) !== false) {
            $result = rtrim(rtrim($result, '0'), $decimal_sep);
        }

        return $result;
    }
}

if (!function_exists('_get_exception_message')) {
    function _get_exception_message(Exception $exception, $withCode = true)
    {
        if ($exception->getCode() == 23000 && config('app.debug') === false) {
            return 'Kode item telah terpakai / duplikat.';
        }

        if ($exception->getCode() == 1062 && config('app.debug') === false) {
            return 'Kode item duplikat.';
        }

        $random = \Illuminate\Support\Str::random(6);

        // for logging message
        $username = optional(auth()->user())->username ?? 'NO_USER';
        $message = "[$random] [$username] - ";
        $message .= $exception->getMessage() . "\n\n";
        $message .= "Stack Trace: \n";
        $message .= $exception->getTraceAsString();
        Log::debug($message);

        // for displaying message
        if ($withCode) {
            return $exception->getMessage() . " [$random]";
        }

        return $exception->getMessage();
    }
}

if (!function_exists('_terbilang')) {
    function _terbilang($amount, $locale = 'id')
    {
        $formatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);

        return $formatter->format($amount);
    }
}

if (!function_exists('_terbilang_upper')) {
    function _terbilang_upper($amount, $locale = 'id', $is_rupiah = true)
    {
        $result = _terbilang($amount, $locale);
        if ($is_rupiah) {
            $result .= ' rupiah';
        }
        $result = strtoupper($result);

        return $result;
    }
}

if (!function_exists('_terbilang_ucfirst')) {
    function _terbilang_ucfirst($amount, $locale = 'id', $is_rupiah = true)
    {
        $result = _terbilang($amount, $locale);
        if ($is_rupiah) {
            $result .= ' rupiah';
        }
        $result = ucfirst($result);

        return $result;
    }
}

if (!function_exists('_terbilang_title')) {
    function _terbilang_title($amount, $locale = 'id', $is_rupiah = true)
    {
        $result = _terbilang($amount, $locale);
        if ($is_rupiah) {
            $result .= ' rupiah';
        }
        $result = str($result)->title()->toString();

        return $result;
    }
}

if (!function_exists('_terbilang_ucwords')) {
    function _terbilang_ucwords($amount, $locale = 'id', $is_rupiah = true)
    {
        $result = _terbilang($amount, $locale);
        if ($is_rupiah) {
            $result .= ' rupiah';
        }
        $result = ucwords($result);

        return $result;
    }
}

if (!function_exists('_get_details_model_function')) {
    function _get_details_model_function($model)
    {
        $className = _get_class_name($model);

        return Str::of($className)->camel() . 'Details';
    }
}

if (!function_exists('_date_format_output')) {
    function _date_format_output($date)
    {
        if ($date == null) {
            return;
        }

        if ($date instanceof Carbon) {
            return $date->format(Const_Date::DATE_FORMAT_OUTPUT);
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATE_FORMAT_DB, $date)->format(Const_Date::DATE_FORMAT_OUTPUT);
        } catch (Exception $e) {
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_DB, $date)->format(Const_Date::DATE_FORMAT_OUTPUT);
        } catch (Exception $e) {
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $date)->format(Const_Date::DATE_FORMAT_OUTPUT);
        } catch (Exception $e) {
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_OUTPUT, $date)->format(Const_Date::DATE_FORMAT_OUTPUT);
        } catch (Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('_date_format_db')) {
    function _date_format_db($date)
    {
        if ($date == null) {
            return;
        }

        if ($date instanceof Carbon) {
            return $date->format(Const_Date::DATE_FORMAT_DB);
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_OUTPUT, $date)->format(Const_Date::DATE_FORMAT_DB);
        } catch (Exception $e) {
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $date)->format(Const_Date::DATE_FORMAT_DB);
        } catch (Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('_datetime_format_db')) {
    function _datetime_format_db($date)
    {
        if ($date == null) {
            return;
        }

        if ($date instanceof Carbon) {
            return $date->format(Const_Date::DATETIME_FORMAT_DB);
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_OUTPUT, $date)->format(Const_Date::DATETIME_FORMAT_DB);
        } catch (Exception $e) {
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $date)->format(Const_Date::DATETIME_FORMAT_DB);
        } catch (Exception $e) {
            return $date;
        }
    }
}

if (!function_exists('_datetime_carbon_db')) {
    function _datetime_carbon_db($date)
    {
        if ($date == null) {
            return;
        }

        if ($date instanceof Carbon) {
            return $date;
        }

        if ($date instanceof DateTime) {
            return new Carbon($date);
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATETIME_FORMAT_OUTPUT, $date);
        } catch (Exception $e) {
        }

        try {
            return Carbon::createFromFormat(Const_Date::DATE_FORMAT_OUTPUT, $date);
        } catch (Exception $e) {
        }

        return $date;
    }
}

if (!function_exists('_get_formatted_indonesian_date')) {
    function _get_formatted_indonesian_date(Carbon $date)
    {
        return $date->isoFormat('dddd, D MMMM Y');
    }
}

if (!function_exists('_get_code_number')) {
    function _get_code_number($CLASS, $data = [], $update = false)
    {
        return (new $CLASS())->getAutoNumber($data, $update);
    }
}

if (!function_exists('_insert_timestamp_before_extension')) {
    function _insert_timestamp_before_extension($string)
    {
        $timestamp = date('Ymd_His');
        $extension_pos = strrpos($string, '.');
        if ($extension_pos) {
            return substr($string, 0, $extension_pos) . '_' . $timestamp . substr($string, $extension_pos);
        }

        return $string;
    }
}

if (!function_exists('_convert_pdf_output_to_base64')) {
    function _convert_pdf_output_to_base64(string $string)
    {
        return chunk_split(base64_encode($string));
    }
}

if (!function_exists('_convert_image_to_base64')) {
    function _convert_image_to_base64($filename, $disk = 'public')
    {
        if (!$filename) {
            return;
        }

        if (Storage::disk($disk)->exists($filename)) {
            $path = Storage::disk($disk)->path($filename);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

            return $base64;
        }

        return;
    }
}

if (!function_exists('_get_default_date_range')) {
    function _get_default_date_range($is_from_begining = false, $tanggal_akhir = null, $tanggal_awal = null)
    {
        if ($is_from_begining) {
            $tanggal_awal = Carbon::create(2000)->startOfYear();
        }

        if (!$tanggal_awal) {
            $tanggal_awal = Carbon::now()->startOfMonth();
        }

        if (!$tanggal_akhir) {
            $tanggal_akhir = Carbon::now()->endOfMonth();
        }

        $tanggal_awal = _date_format_output($tanggal_awal);
        $tanggal_akhir = _date_format_output($tanggal_akhir);

        return $tanggal_awal . ' - ' . $tanggal_akhir;
    }
}

if (!function_exists('_get_default_datetime_range')) {
    function _get_default_datetime_range($is_from_begining = false)
    {
        $tanggal_awal = Carbon::now()->startOfMonth()->format(Const_Date::DATETIME_FORMAT_OUTPUT);
        if ($is_from_begining) {
            $tanggal_awal = Carbon::create(2000)->startOfYear()->format(Const_Date::DATETIME_FORMAT_OUTPUT);
        }

        $tanggal_akhir = Carbon::now()->endOfMonth()->format(Const_Date::DATETIME_FORMAT_OUTPUT);

        return $tanggal_awal . ' - ' . $tanggal_akhir;
    }
}

if (!function_exists('_get_default_date')) {
    function _get_default_date()
    {
        return Carbon::now()->format(Const_Date::DATE_FORMAT_OUTPUT);
    }
}

if (!function_exists('_get_default_datetime')) {
    function _get_default_datetime()
    {
        return Carbon::now()->format(Const_Date::DATETIME_FORMAT_OUTPUT);
    }
}

if (!function_exists('_datetime_carbon_split_filter_date')) {
    function _datetime_carbon_split_filter_date($date_range)
    {
        $tanggals = explode(' - ', $date_range);
        $tanggal_awal = _datetime_carbon_db($tanggals[0])->startOfDay();
        $tanggal_akhir = $tanggal_awal->clone()->endOfDay();
        if (count($tanggals) > 1) {
            $tanggal_akhir = _datetime_carbon_db($tanggals[1])->endOfDay();
        }

        return [$tanggal_awal, $tanggal_akhir];
    }
}

if (!function_exists('_get_homepage_route')) {
    function _get_homepage_route()
    {
        $user = Auth::user();
        if (optional($user)->type == Const_Umum::USER_TYPE_ADMIN) {
            return 'admin.dashboard';
        }

        return 'admin.login';
    }
}

if (!function_exists('_round')) {
    function _round($value, $decimal = 4)
    {
        return round($value, $decimal);
    }
}

if (!function_exists('_get_hex_colors')) {
    function _get_hex_colors()
    {
        return [
            '#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0',
            '#3F51B5', '#03A9F4', '#4CAF50', '#F9CE1D', '#FF9800',
            '#33B2DF', '#546E7A', '#D4526E', '#13D8AA', '#A5978B',
            '#4ECDC4', '#C7F464', '#81D4FA', '#546E7A', '#FD6A6A',
            '#2B908F', '#F9A3A4', '#90EE7E', '#FA4443', '#69D2E7',
            '#449DD1', '#F86624', '#EA3546', '#662E9B', '#C5D86D',
            '#D7263D', '#1B998B', '#2E294E', '#F46036', '#E2C044',
            '#662E9B', '#F86624', '#F9C80E', '#EA3546', '#43BCCD',
            '#5C4742', '#A5978B', '#8D5B4C', '#5A2A27', '#C4BBAF',
            '#A300D6', '#7D02EB', '#5653FE', '#2983FF', '#00B1F2',
        ];
    }
}

if (!function_exists('_get_month_name')) {
    function _get_month_name($month)
    {
        if ($month == 1) {
            return 'Januari';
        } elseif ($month == 2) {
            return 'Februari';
        } elseif ($month == 3) {
            return 'Maret';
        } elseif ($month == 4) {
            return 'April';
        } elseif ($month == 5) {
            return 'Mei';
        } elseif ($month == 6) {
            return 'Juni';
        } elseif ($month == 7) {
            return 'Juli';
        } elseif ($month == 8) {
            return 'Agustus';
        } elseif ($month == 9) {
            return 'September';
        } elseif ($month == 10) {
            return 'Oktober';
        } elseif ($month == 11) {
            return 'November';
        } elseif ($month == 12) {
            return 'Desember';
        }

        return '';
    }
}

if (!function_exists('_invertHexColor')) {
    function _invertHexColor($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) !== 6) {
            return '#000000';
        }
        $new = '';
        for ($i = 0; $i < 3; $i++) {
            $rgbDigits = 255 - hexdec(substr($hex, (2 * $i), 2));
            $hexDigits = ($rgbDigits < 0) ? 0 : dechex($rgbDigits);
            $new .= (strlen($hexDigits) < 2) ? '0' . $hexDigits : $hexDigits;
        }
        return '#' . $new;
    }
}

if (!function_exists('_ppn_value')) {
    function _ppn_value($harga, $ppn_persen, $is_include_ppn = false)
    {
        $ppn = ($harga * $ppn_persen / 100.0);
        if ($is_include_ppn) {
            $ppn = ($harga * $ppn_persen / 100) / (1 + $ppn_persen / 100);
        }

        $ppn = floor($ppn);
        return $ppn;
    }
}
