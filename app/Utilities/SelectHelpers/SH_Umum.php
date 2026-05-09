<?php

namespace App\Utilities\SelectHelpers;

use Illuminate\Support\Collection;
use App\Utilities\Constants\Const_Umum;

class SH_Umum
{
    public static function tahun(): Collection
    {
        $result = [];

        $yearStart = 2024;
        $yearNow = date('Y');

        for ($i = $yearStart; $i <= $yearNow; $i++) {
            $result[$i] = $i;
        }

        return collect($result);
    }

    public static function bulan(): Collection
    {
        $result = [];

        $result[1] = 'Januari';
        $result[2] = 'Februari';
        $result[3] = 'Maret';
        $result[4] = 'April';
        $result[5] = 'Mei';
        $result[6] = 'Juni';
        $result[7] = 'Juli';
        $result[8] = 'Agustus';
        $result[9] = 'Oktober';
        $result[10] = 'September';
        $result[11] = 'November';
        $result[12] = 'Desember';

        return collect($result);
    }

    public static function hari(): Collection
    {
        $result = [];

        $result['Senin'] = 'Senin';
        $result['Selasa'] = 'Selasa';
        $result['Rabu'] = 'Rabu';
        $result['Kamis'] = 'Kamis';
        $result['Jumat'] = 'Jumat';
        $result['Sabtu'] = 'Sabtu';
        $result['Minggu'] = 'Minggu';

        return collect($result);
    }

    public static function jenisSyaratPromo(): Collection
    {
        $result = [];

        $result[Const_Umum::JENIS_SYARAT_PROMO_BELI_SEMUA_PRODUK] = Const_Umum::JENIS_SYARAT_PROMO_BELI_SEMUA_PRODUK;
        $result[Const_Umum::JENIS_SYARAT_PROMO_BELI_PRODUK_TERTENTU] = Const_Umum::JENIS_SYARAT_PROMO_BELI_PRODUK_TERTENTU;
        $result[Const_Umum::JENIS_SYARAT_PROMO_MINIMAL_TRANSAKSI] = Const_Umum::JENIS_SYARAT_PROMO_MINIMAL_TRANSAKSI;

        return collect($result);
    }

    public static function jenisManfaatPromo(): Collection
    {
        $result = [];

        $result[Const_Umum::JENIS_MANFAAT_PROMO_DISKON_PENJUALAN] = Const_Umum::JENIS_MANFAAT_PROMO_DISKON_PENJUALAN;
        $result[Const_Umum::JENIS_MANFAAT_PROMO_PRODUK_GRATIS] = Const_Umum::JENIS_MANFAAT_PROMO_PRODUK_GRATIS;

        return collect($result);
    }

    public static function logikaSyaratPromo(): Collection
    {
        $result = [];

        $result[Const_Umum::LOGIKA_SYARAT_PROMO_DAN] = Const_Umum::LOGIKA_SYARAT_PROMO_DAN;
        $result[Const_Umum::LOGIKA_SYARAT_PROMO_ATAU] = Const_Umum::LOGIKA_SYARAT_PROMO_ATAU;

        return collect($result);
    }

    public static function logikaManfaatPromo(): Collection
    {
        $result = [];

        $result[Const_Umum::LOGIKA_MANFAAT_PROMO_DAN] = Const_Umum::LOGIKA_MANFAAT_PROMO_DAN;
        $result[Const_Umum::LOGIKA_MANFAAT_PROMO_ATAU] = Const_Umum::LOGIKA_MANFAAT_PROMO_ATAU;

        return collect($result);
    }

    public static function jenisTransaksiFakturPembelian(): Collection
    {
        $result = [];

        $result[Const_Umum::JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_KREDIT] = Const_Umum::JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_KREDIT;
        $result[Const_Umum::JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_LUNAS] = Const_Umum::JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_LUNAS;

        return collect($result);
    }

    public static function jenisTransaksiFakturPenjualan(): Collection
    {
        $result = [];

        $result[Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT] = Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT;
        $result[Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS] = Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS;

        return collect($result);
    }
}
